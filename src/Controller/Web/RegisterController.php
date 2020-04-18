<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Entity\Account;
use App\Exception\AccountCreationException;
use App\Form\RegisterType;
use App\Service\AccountManager;
use App\Service\GoogleRecaptchaApiServiceInterface;
use App\StaticData\Organizations;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RegisterController extends AbstractController
{
    /**
     * @var GoogleRecaptchaApiServiceInterface
     */
    private $googleRecaptchaApi;

    /**
     * @var AccountManager
     */
    private $accountManager;

    public function __construct(
        GoogleRecaptchaApiServiceInterface $googleRecaptchaApi,
        AccountManager $accountManager
    ) {
        $this->googleRecaptchaApi = $googleRecaptchaApi;
        $this->accountManager = $accountManager;
    }

    /**
     * @Route("/registrierung", name="register", methods={"GET"})
     */
    public function register(): Response
    {
        return $this->render('web/register/register.html.twig', [
            'organisations' => Organizations::DATA,
        ]);
    }

    /**
     * @Route("/registrierung", name="register_post", methods={"POST"})
     *
     * @throws AccountCreationException
     */
    public function registrierungPost(Request $request): Response
    {
        $form = $this->createForm(RegisterType::class);
        $form->submit($request->request->all());

        if (!$form->isSubmitted()) {
            dd($request->request->all());

            return $this->redirectToRoute('web_register');
        }

        if (!$form->isValid()) {
            return $this->render('web/register/error.html.twig', [
                'message' => 'Da ist etwas schiefgelaufen, probiere es doch noch einmal',
            ]);
        }

        /** @var Account $account */
        $account = $form->getData();

        $score = $this->googleRecaptchaApi->verify($request->request->get('token'));
        $account->setScore($score);

        $this->accountManager->register($account);

        return $this->render('web/register/confirmation-necessary.twig');
    }

    /**
     * @Route("/confirm/{token}", name="confirm", methods={"GET"})
     */
    public function confirm(Account $account): Response
    {
        $this->accountManager->verifyEmail($account);

        return $this->render('web/register/confirmation.html.twig');
    }
}
