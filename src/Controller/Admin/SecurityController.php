<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Invitation;
use App\Form\Admin\RegisterType;
use App\Security\InvitationManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("", name="security_")
 */
class SecurityController extends AbstractController
{
    /**
     * @var InvitationManager
     */
    private $invitationManager;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(InvitationManager $invitationManager, EntityManagerInterface $entityManager)
    {
        $this->invitationManager = $invitationManager;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/security/login.html.twig', ['last_username' => $lastUsername, 'error_message' => $error]);
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register/{id}", name="register", methods={"GET", "POST"})
     */
    public function register(Invitation $invitation, Request $request): Response
    {
        if (!$this->invitationManager->isInvitationValid($invitation)) {
            return $this->render('admin/security/expired_invitation.html.twig');
        }

        $admin = new Admin();
        $admin->setEmail($invitation->getEmail());
        $admin->setRoles($invitation->getRoles());

        $form = $this->createForm(RegisterType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->remove($invitation);
            $this->entityManager->persist($admin);
            $this->entityManager->flush();

            return $this->redirectToRoute('web_imprint');
        }

        return $this->render('admin/security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
