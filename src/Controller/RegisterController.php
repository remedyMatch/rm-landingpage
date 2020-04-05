<?php

namespace App\Controller;

use App\Entity\Account;
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

class RegisterController extends AbstractController
{
    private $router;
    private $mailer;
    private $session;
    private $keycloakRestApi;
    private $organisations = [
        [
            'value' => 1,
            'label' => 'Pflegende Angehörige'
        ],
        [
            'value' => 2,
            'label' => 'Pflegedienst'
        ],
        [
            'value' => 3,
            'label' => 'Krankenhaus'
        ],
        [
            'value' => 4,
            'label' => 'Gewerbe und Industrie'
        ],
    ];

    public function __construct(
        UrlGeneratorInterface $router,
        MailerInterface $mailer,
        SessionInterface $session,
        KeycloakRestApiService $keycloakRestApi
    ) {
        $this->router = $router;
        $this->mailer = $mailer;
        $this->session = $session;
        $this->keycloakRestApi = $keycloakRestApi;
    }

    /**
     * @Route({ "de": "/registrierung", "en": "/register" }, name="registrierung", methods={"GET"})
     * @return ResponseAlias
     */
    public function registrierung()
    {
        return $this->render('register/registrierung.html.twig', [
            'organisations' => $this->organisations
        ]);
    }

    /**
     * @Route({ "de": "/registrierung", "en": "/register" }, name="registrierung_post", methods={"POST"})
     * @param Request $request
     * @return ResponseAlias
     * @throws TransportExceptionInterface
     */
    public function registrierungPost(Request $request)
    {
        if ($this->handleRegistration($request)) {
            return $this->render('register/bestaetigung.html.twig');
        } else {
            return $this->render('register/fehler.html.twig', [
                'message' => 'Ihre E-Mailadresse ist bereits registriert.'
            ]);
        }
    }

    /**
     * @param Request $request
     * @return bool
     * @throws TransportExceptionInterface
     */
    private function handleRegistration(
        Request $request
    ) {
        $entityManager = $this->getDoctrine()->getManager();

        // Daten validieren
        $uniqueId = uniqid();
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
        $account->setPassword("");
        try {
            $entityManager->persist($account);
            $entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {

        }
        $handle=fopen("Id.txt","w");
        fwrite($handle,$confirmLink);

        // Add user to keycloak
        if (!$this->createKeycloakAccount($request)) {
            return false;
        }
        // prepare email
        $email = (new TemplatedEmail())
            ->from(new Address('info@remedymatch.io', 'RemedyMatch.io'))
            ->to(new Address($account->getEmail(),
                !empty($account->getCompany()) ? $account->getCompany() : $account->getFirstname() . ' ' . $account->getLastname()))
            ->subject('Aktivieren Sie Ihren Zugang für RemedyMatch.io')
            ->htmlTemplate('emails/account-confirm.html.twig')
            ->context([
                'firstname' => $account->getFirstname(),
                'confirmLink' => $confirmLink
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
     * @param Request $request
     * @return bool
     */
    private function createKeycloakAccount(Request $request)
    {

        $groupname='Privatperson-'. $request->get('email').'-'.uniqid();

        $group = [
            # 'access' => '',
            # 'attributes' => '',
            # 'clientRoles' => '',
            # 'id' => '',
            'name' =>$groupname,
            # 'path' => 'private-persons',
            # 'realmRoles' => [],
            # 'subGroups' => [],
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
                    'temporary' => false
                ]
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
                'country' => 'germany'
            ],
            'groups'=>[
                $group['name']
                ]
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
     * @Route({ "de": "/anmeldung-bestaetigen/{token}", "en": "//confirm/{token}" }, name="confirm")
     * @param string $token
     * @return ResponseAlias
     * @throws \Exception
     */
    public function confirm(string $token)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $account = $entityManager->getRepository(Account::class)->findOneBy(['token' => $token]);

        
        if (!$account instanceof Account) {
            return $this->render('register/fehler.html.twig');
        }

        $account->setVerifiedAt(new \DateTime());
        $entityManager->persist($account);
        $entityManager->flush();

        $users = $this->keycloakRestApi->getUsers($account->getEmail());

        $users[0]->attributes->status="verifiziert";
        $users[0]->emailVerified = "true";
        $this->keycloakRestApi->updateUser($users[0]->id,$users[0]);

        return $this->render('register/bestaetigung.html.twig');
    }
}