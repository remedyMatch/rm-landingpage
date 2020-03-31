<?php

namespace App\Controller;

use App\Entity\Account;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use JoliCode\Slack\ClientFactory;
use JoliCode\Slack\Exception\SlackErrorResponse;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
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

    public function __construct(UrlGeneratorInterface $router, MailerInterface $mailer)
    {
        $this->router = $router;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/registrierung", name="registrierung")
     * @param Request $request
     * @param MailerInterface $mailer
     * @return ResponseAlias
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function registrierung(Request $request, MailerInterface $mailer)
    {
        if ($request->get('firstname')) {
            $this->handleRegistration($request);
            return $this->render('register/bestaetigung-erforderlich.html.twig');
        }

        $organisations = [
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

        return $this->render('register/registrierung.html.twig', [
            'organisations' => $organisations,
            'success' => $request->get('success')
        ]);
    }

    /**
     * @param Request $request
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    private function handleRegistration(Request $request)
    {
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
        $account->setPassword($request->get('password'));
        $account->setType($request->get('type'));
        $account->setCompany($request->get('company'));
        $account->setToken($uniqueId);
        try {
            $entityManager->persist($account);
            $entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {

        }

        // prepare email
        $email = (new TemplatedEmail())
            ->from(new Address('info@remedymatch.io', 'RemedyMatch.io'))
            ->to(new Address($account->getEmail(), !empty($account->getCompany()) ? $account->getCompany() : $account->getFirstname() . ' ' . $account->getLastname()))
            ->subject('Aktivieren Sie Ihren Zugang für RemedyMatch.io')
            ->htmlTemplate('emails/account-confirm.html.twig')
            ->context([
                'firstname' => $account->getFirstname(),
                'confirmLink' => $confirmLink
            ]);

        $this->mailer->send($email);

        // Slack message senden
        $token = $this->getParameter('app.slack_token');
        $client = ClientFactory::create($token);

        // This method requires your token to have the scope "chat:write"
        $result = $client->chatPostMessage([
            'username' => 'remedybot',
            'channel' => 'sandkasten',
            'text' => 'Es hat sich ein neuer Benutzer (' . $uniqueId . ') registriert.',
        ]);
    }

    /**
     * @Route("/confirm/{token}", name="confirm")
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

        // Slack message senden
        $token = $this->getParameter('app.slack_token');
        $client = ClientFactory::create($token);

        // This method requires your token to have the scope "chat:write"
        $result = $client->chatPostMessage([
            'username' => 'remedybot',
            'channel' => 'sandkasten',
            'text' => 'Ein  Benutzer (' . $account->getToken() . ') hat seinen Account aktiviert.',
        ]);

        return $this->render('register/bestaetigung.html.twig');
    }
}