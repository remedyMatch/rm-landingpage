<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Account;
use App\Exception\AccountCreationException;
use App\Exception\KeycloakException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class AccountManager implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var KeycloakManager
     */
    private $keycloakManager;

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var SlackNotifierServiceInterface
     */
    private $slackNotifier;

    public function __construct(
        KeycloakManager $keycloakManager,
        MailerInterface $mailer,
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        SlackNotifierServiceInterface $slackNotifier
    ) {
        $this->keycloakManager = $keycloakManager;
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->slackNotifier = $slackNotifier;
    }

    /**
     * @throws AccountCreationException
     */
    public function register(Account $account): void
    {
        try {
            $this->keycloakManager->createAccount($account);
        } catch (KeycloakException $exception) {
            $message = 'User could not be registered due to keycloak problems';
            $this->logger->error($message, [
                'account' => $account,
            ]);
            throw new AccountCreationException($message, 4820244, $e);
        }

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        try {
            $this->sendConfirmationEmail($account);
        } catch (TransportExceptionInterface $e) {
            $message = 'Could not send confirmation email, keycloak user was created';
            $this->logger->critical($message, [
                'account' => $account,
            ]);
            throw new AccountCreationException($message, 2347283, $e);
        }

        $this->slackNotifier->sendNotification('Es hat sich ein neuer Benutzer registriert.');
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function sendConfirmationEmail(Account $account): void
    {
        $uniqueId = md5($account->getEmail().md5('RemedyMatchSalt'));

        $confirmLink = $this->router->generate('web_confirm', ['token' => $uniqueId],
            UrlGeneratorInterface::ABSOLUTE_URL);

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

        $account->setToken($uniqueId);
        $this->entityManager->flush();
    }

    public function approve(Account $account)
    {
        $now = new \DateTime();
        $account->setVerifiedAt($now);
        $account->setReviewedAt($now);
        $account->setReviewer(isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '');
        $account->setIsRejected(false);

        $this->entityManager->flush();

        $this->keycloakManager->approveAccount($account->getEmail());
    }

    public function verifyEmail(Account $account)
    {
        $account->setVerifiedAt(new \DateTime());
        $this->entityManager->flush();

        $this->keycloakManager->verifyEmailAccount($account->getEmail());
    }
}
