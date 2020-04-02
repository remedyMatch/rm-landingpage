<?php

namespace App\Controller;

use App\Entity\Account;
use App\Service\KeycloakRestApiService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use JoliCode\Slack\ClientFactory;
use JoliCode\Slack\Exception\SlackErrorResponse;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

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
     * @Route("/registrierung", name="registrierung")
     * @param Request $request
     * @param MailerInterface $mailer
     * @return ResponseAlias
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function registrierung(
        Request $request,
        MailerInterface $mailer
    ) {
        if ($request->get('firstname')) {
            if ($this->handleRegistration($request)) {
                return $this->render('register/bestaetigung.html.twig');
            } else {
                return $this->render('register/fehler.html.twig', [
                    'message' => 'Ihre E-Mailadresse ist bereits registriert.'
                ]);
            }
        }

        return $this->render('register/registrierung.html.twig', [
            'organisations' => $this->organisations
        ]);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function handleRegistration(
        Request $request
    ) {
        // Add user to keycloak
        if (!$this->createKeycloakAccount($request)) {
            return false;
        }
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
        $group = [
            # 'access' => '',
            # 'attributes' => '',
            # 'clientRoles' => '',
            # 'id' => '',
            'name' => $request->get('company'),
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
                'country' => 'germany'
            ]
        ];
        /*
        $user['groups'] = [
            $group['name']
        ];
        */
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
}