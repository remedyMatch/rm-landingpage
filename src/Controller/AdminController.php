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

    public function __construct(
        AccountRepository $accountRepository
    ) {
        $this->accountRepository = $accountRepository;
    }

    /**
     * @Route("/admin", name="admin", methods={"GET"})
     * @param Request $request
     * @return ResponseAlias
     */
    public function admin(Request $request)
    {
        switch($request->get('action')) {
            case 'reject':
                $account = $this->accountRepository->findOneByEmail($request->get('email'));
                if(!$account)
                    throw new NotFoundException();

                echo "TODO: Reject Account".$account->getId();
                break;

            case 'validate':
                $account = $this->accountRepository->findOneByEmail($request->get('email'));
                if(!$account)
                    throw new NotFoundException();

                echo "TODO: Validate Account".$account->getId();

                break;
        }

        return $this->render('admin.html.twig', [
            'accounts' => $this->accountRepository->findUnreviewed()
        ]);
    }
}