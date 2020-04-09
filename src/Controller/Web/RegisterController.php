<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Entity\Account;
use App\Service\GoogleRecaptchaApiService;
use App\Service\KeycloakRestApiService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use GuzzleHttp\Exception\ClientException;
use JoliCode\Slack\ClientFactory;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RegisterController extends AbstractController
{
    /** @var GoogleRecaptchaApiService */
    protected $googleRecaptchaApi;
    private $router;
    private $mailer;
    private $session;
    private $keycloakRestApi;
    private $organisations = [
        [
            'value' => 1,
            'label' => 'Pflegende Angehörige',
        ],
        [
            'value' => 2,
            'label' => 'Pflegedienst',
        ],
        [
            'value' => 3,
            'label' => 'Krankenhaus',
        ],
        [
            'value' => 4,
            'label' => 'Gewerbe und Industrie',
        ],
    ];

    public function __construct(
        UrlGeneratorInterface $router,
        MailerInterface $mailer,
        SessionInterface $session,
        KeycloakRestApiService $keycloakRestApi,
        GoogleRecaptchaApiService $googleRecaptchaApi
    ) {
        $this->router = $router;
        $this->mailer = $mailer;
        $this->session = $session;
        $this->keycloakRestApi = $keycloakRestApi;
        $this->googleRecaptchaApi = $googleRecaptchaApi;
    }

    /**
     * @Route("/registrierung", name="register", methods={"GET"})
     *
     * @return ResponseAlias
     */
    public function register()
    {
        return $this->render('register/registrierung.html.twig', [
            'organisations' => $this->organisations,
        ]);
    }

    /**
     * @Route("/registrierung", name="register_post", methods={"POST"})
     *
     * @return ResponseAlias
     *
     * @throws TransportExceptionInterface
     */
    public function registrierungPost(Request $request)
    {
        if ($this->handleRegistration($request)) {
            return $this->render('register/bestaetigung.html.twig');
        } else {
            return $this->render('register/fehler.html.twig', [
                'message' => 'Ihre E-Mailadresse ist bereits registriert.',
            ]);
        }
    }

    /**
     * @return bool
     *
     * @throws TransportExceptionInterface
     */
    private function handleRegistration(
        Request $request
    ) {
        $entityManager = $this->getDoctrine()->getManager();

        // Daten validieren
        $uniqueId = md5($request->get('email').md5('RemedyMatchSalt'));
        $confirmLink = $this->router->generate('confirm', ['token' => $uniqueId], UrlGeneratorInterface::ABSOLUTE_URL);

        // Daten abspeichern
        $account = new Account();
        $account->setFirstname($request->get('firstname'));
        $account->setLastname($request->get('lastname'));
        $account->setEmail($request->get('email'));
        $account->setStreet($request->get('street'));
        $account->setHousenumber($request->get('housenumber'));
        $account->setZipcode($request->get('zipcode'));
        $account->setCity($request->get('city'));
        $account->setPhone($request->get('phone'));
        $account->setType($request->get('type'));
        $account->setCompany($request->get('company'));
        $account->setToken($uniqueId);
        try {
            $score = $this->googleRecaptchaApi->verify($request->get('token'));
            $account->setScore($score);
        } catch (\Exception $exception) {
        }

        try {
            $entityManager->persist($account);
            $entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
        }

        // Add user to keycloak
        if (!$this->createKeycloakAccount($request)) {
            return false;
        }
        // prepare email
        $email = (new TemplatedEmail())
            ->from(new Address('info@remedymatch.io', 'RemedyMatch.io'))
            ->to(new Address($account->getEmail(),
                !empty($account->getCompany()) ? $account->getCompany() : $account->getFirstname().' '.$account->getLastname()))
            ->subject('Aktivieren Sie Ihren Zugang für RemedyMatch.io')
            ->htmlTemplate('emails/account-confirm.html.twig')
            ->context([
                'firstname' => $account->getFirstname(),
                'confirmLink' => $confirmLink,
            ]);

        $this->mailer->send($email);

        // send to slack
        // Slack message senden
        $token = $this->getParameter('app.slack_token');
        $client = ClientFactory::create($token);

        // This method requires your token to have the scope "chat:write"
        $result = $client->chatPostMessage([
            'username' => 'remedybot',
            'channel' => 'sandkasten',
            'text' => 'Es hat sich ein neuer Benutzer registriert.',
        ]);

        return true;
    }

    /**
     * @return bool
     */
    private function createKeycloakAccount(Request $request)
    {
        if ('' == $request->get('company')) {
            $groupname = 'Privatperson-'.$request->get('email');
        } else {
            $groupname = $request->get('company');
        }

        $group = [
            // 'access' => '',
            // 'attributes' => '',
            // 'clientRoles' => '',
            // 'id' => '',
            'name' => $groupname,
            // 'path' => 'private-persons',
            // 'realmRoles' => [],
            // 'subGroups' => [],
        ];

        $user = [
            'email' => $request->get('email'),
            'username' => $request->get('email'),
            'firstName' => $request->get('firstname'),
            'lastName' => $request->get('lastname'),
            'enabled' => false,
            'emailVerified' => false,
            'credentials' => [
                [
                    'type' => 'password',
                    'value' => $request->get('password'),
                    'temporary' => false,
                ],
            ],
            'attributes' => [
                'company' => $request->get('company'),
                'company-type' => $request->get('type'),
                'street' => $request->get('street'),
                'housenumber' => $request->get('housenumber'),
                'zipcode' => $request->get('zipcode'),
                'city' => $request->get('city'),
                'phone' => $request->get('phone'),
                'status' => 'NEU',
                'country' => 'germany',
            ],
            'groups' => [
                $group['name'],
            ],
        ];

        try {
            $this->keycloakRestApi->addGroup($group);
        } catch (\Exception $exception) {
        }

        try {
            $this->keycloakRestApi->addUser($user);
        } catch (ClientException $clientException) {
            return false;
        }

        return true;
    }

    /**
     * @Route("/confirm/{token}", name="confirm", methods={"GET"})
     *
     * @return ResponseAlias
     *
     * @throws \Exception
     */
    public function confirm(string $token)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $account = $entityManager->getRepository(Account::class)->findOneBy(['token' => $token]);

        if (!$account instanceof Account) {
            return $this->render('register/fehler.html.twig', [
                'message' => 'Es gab ein Problem mit der Aktivierung Ihres Accounts. Bitte registrieren Sie sich erneut.',
            ]);
        }

        $account->setVerifiedAt(new \DateTime());
        $entityManager->persist($account);
        $entityManager->flush();

        $users = $this->keycloakRestApi->getUsers($account->getEmail());

        $users[0]->attributes->status = 'verifiziert';
        $users[0]->emailVerified = true;
        $this->keycloakRestApi->updateUser($users[0]->id, $users[0]);

        return $this->render('register/bestaetigung.html.twig');
    }
}
