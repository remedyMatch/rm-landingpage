<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Service\AccountManager;
use App\Service\KeycloakRestApiServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
 *
 * @IsGranted("ROLE_DECIDER")
 */
final class RegistrationController extends AbstractController
{
    /**
     * @var AccountManager
     */
    private $accountManager;

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @var KeycloakRestApiServiceInterface
     */
    private $keycloakRestApi;

    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(
        AccountRepository $accountRepository,
        AccountManager $accountManager,
        KeycloakRestApiServiceInterface $keycloakRestApi,
        MailerInterface $mailer
    ) {
        $this->accountRepository = $accountRepository;
        $this->accountManager = $accountManager;
        $this->keycloakRestApi = $keycloakRestApi;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/rejected", name="rejected_list", methods={"GET"})
     */
    public function rejectedList(): Response
    {
        return $this->render('admin/registration/rejected-list.html.twig', [
            'rejected_accounts' => $this->accountRepository->findRejected(),
        ]);
    }

    /**
     * @Route("/unreviewed", name="unreviewed_list", methods={"GET"})
     */
    public function unreviewedList(): Response
    {
        return $this->render('admin/registration/unreviewed-list.html.twig', [
            'unreviewed_accounts' => $this->accountRepository->findUnreviewed(),
        ]);
    }

    /**
     * @Route("/accepted", name="accepted_list", methods={"GET"})
     */
    public function acceptedList(): Response
    {
        return $this->render('admin/registration/accepted-list.html.twig', [
            'accepted_accounts' => $this->accountRepository->findAccepted(),
        ]);
    }

    /**
     * @Route("/validate/{account}", name="validate", methods={"POST"})
     *
     * @throws TransportExceptionInterface
     */
    public function validate(Account $account): RedirectResponse
    {
        $this->accountManager->approve($account);

        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@remedymatch.eu', 'RemedyMatch.io'))
            ->to(new Address($account->getEmail(),
                !empty($account->getCompany()) ? $account->getCompany() : $account->getFirstname().' '.$account->getLastname()))
            ->subject('Ihr Zugang für RemedyMatch.io wurde freigeschaltet!')
            ->htmlTemplate('emails/verification/verified.twig')
            ->context([
                'account' => $account,
            ]);

        $this->mailer->send($email);

        return $this->redirectToRoute('admin_registration_unreviewed_list');
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
            ->from(new Address('validation@remedymatch.eu ', 'RemedyMatch.io'))
            ->to(new Address($account->getEmail(),
                !empty($account->getCompany()) ? $account->getCompany() : $account->getFirstname().' '.$account->getLastname()))
            ->subject('Schalten Sie Ihren Zugang zu RemedyMatch.io frei')
            ->htmlTemplate('emails/verification/more-information-required.twig')
            ->context([
                'account' => $account,
            ]);

        $this->mailer->send($email);

        return $this->redirectToRoute('admin_registration_unreviewed_list');
    }
}
