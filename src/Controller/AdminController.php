<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Service\KeycloakRestApiService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use GuzzleHttp\Exception\ClientException;
use Http\Discovery\Exception\NotFoundException;
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

class AdminController extends AbstractController
{
    private $accountRepository;
    private $keycloakRestApi;
    public function __construct(
        AccountRepository $accountRepository,
        KeycloakRestApiService $keycloakRestApi
    ) {
        $this->accountRepository = $accountRepository;
        $this->keycloakRestApi = $keycloakRestApi;
    }

    /**
     * @Route("/admin", name="admin", methods={"GET"})
     * @param Request $request
     * @return ResponseAlias
     */
    public function admin(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        switch($request->get('action')) {
            case 'reject':
                $account = $this->accountRepository->findOneByEmail($request->get('email'));
                if(!$account)
                    throw new NotFoundException();

                //TODO: send rejection email

            
                break;

            case 'validate':
                $account = $this->accountRepository->findOneByEmail($request->get('email'));
                if(!$account)
                    throw new NotFoundException();

                $account->setReviewedAt(new \DateTime());
                $account->setReviewer($_SERVER['PHP_AUTH_USER']);

                $entityManager->persist($account);
                $entityManager->flush();

                //Activate user in keycloak
                $users = $this->keycloakRestApi->getUsers($account->getEmail());

                $users[0]->attributes->status = "Verifiert";
                $users[0]->enabled = true;
                $users[0]->emailVerfied = true;
                $this->keycloakRestApi->updateUser($users[0]->id, $users[0]);

                //TODO: send activation success email

                break;
        }

        return $this->render('admin.html.twig', [
            'accounts' => $this->accountRepository->findUnreviewed()
        ]);
    }
}