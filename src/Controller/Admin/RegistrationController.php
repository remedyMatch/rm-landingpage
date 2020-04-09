<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Service\KeycloakRestApiService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/registration", name="registration_")
 */
final class RegistrationController extends AbstractController
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @var KeycloakRestApiService
     */
    private $keycloakRestApi;

    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(
        AccountRepository $accountRepository,
        KeycloakRestApiService $keycloakRestApi,
        MailerInterface $mailer
    ) {
        $this->accountRepository = $accountRepository;
        $this->keycloakRestApi = $keycloakRestApi;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", name="list", methods={"GET"})
     */
    public function admin(): Response
    {
        return $this->render('admin/admin.html.twig', [
            'unreviewedAccounts' => $this->accountRepository->findUnreviewed(),
            'rejectedAccounts' => $this->accountRepository->findRejected(),
        ]);
    }

    /**
     * @Route("/validate/{account}", name="validate", methods={"POST"})
     *
     * @throws TransportExceptionInterface
     */
    public function validate(Account $account): RedirectResponse
    {
        $now = new \DateTime();
        $account->setVerifiedAt($now);
        $account->setReviewedAt($now);
        $account->setReviewer(isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '');
        $account->setIsRejected(false);

        $this->getDoctrine()->getManager()->persist($account);
        $this->getDoctrine()->getManager()->flush();

        //Activate user in keycloak
        $users = $this->keycloakRestApi->getUsers($account->getEmail());
        $users[0]->attributes->status = 'Verifiert';
        $users[0]->enabled = true;
        $users[0]->emailVerified = true;
        $this->keycloakRestApi->updateUser($users[0]->id, $users[0]);

        $email = (new TemplatedEmail())
            ->from(new Address('info@remedymatch.io', 'RemedyMatch.io'))
            ->to(new Address($account->getEmail(),
                !empty($account->getCompany()) ? $account->getCompany() : $account->getFirstname().' '.$account->getLastname()))
            ->bcc('julian@remedymatch.io')
            ->subject('Ihr Zugang für RemedyMatch.io wurde freigeschaltet!')
            ->htmlTemplate('emails/verification/verified.twig')
            ->context([
                'account' => $account,
            ]);

        $this->mailer->send($email);

        return $this->redirectToRoute('admin_registration_list');
    }

    /**
     * @Route("/reject/{account}", name="reject", methods={"POST"})
     *
     * @throws TransportExceptionInterface
     */
    public function reject(Account $account): RedirectResponse
    {
        $account->setIsRejected(true);
        $account->setReviewedAt(new \DateTime());
        $account->setReviewer(isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '');

        $this->getDoctrine()->getManager()->persist($account);
        $this->getDoctrine()->getManager()->flush();

        $email = (new TemplatedEmail())
            ->from(new Address('info@remedymatch.io', 'RemedyMatch.io'))
            ->to(new Address($account->getEmail(),
                !empty($account->getCompany()) ? $account->getCompany() : $account->getFirstname().' '.$account->getLastname()))
            ->bcc('julian@remedymatch.io')
            ->subject('Schalten Sie Ihren Zugang zu RemedyMatch.io frei')
            ->htmlTemplate('emails/verification/more-information-required.twig')
            ->context([
                'account' => $account,
            ]);

        $this->mailer->send($email);

        return $this->redirectToRoute('admin_registration_list');
    }
}
