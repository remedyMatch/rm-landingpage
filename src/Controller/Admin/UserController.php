<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Invitation;
use App\Form\Admin\InviteType;
use App\Form\Admin\UserRolesType;
use App\Repository\AdminRepository;
use App\Repository\InvitationRepository;
use App\Security\InvitationManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="user_")
 *
 * @IsGranted("ROLE_ADMIN")
 */
final class UserController extends AbstractController
{
    private $adminRepository;
    private $invitationRepository;
    private $entityManager;

    public function __construct(
        AdminRepository $adminRepository,
        InvitationRepository $invitationRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->adminRepository = $adminRepository;
        $this->invitationRepository = $invitationRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="list", methods={"GET"})
     */
    public function list(): Response
    {
        $admins = $this->adminRepository->findAll();

        return $this->render('admin/user/list.html.twig', [
            'admins' => $admins,
        ]);
    }

    /**
     * @Route("/invitations", name="invitations_list", methods={"GET"})
     */
    public function listInvitations(): Response
    {
        $invitations = $this->invitationRepository->findAll();

        return $this->render('admin/user/invitation-list.html.twig', [
            'invitations' => $invitations,
        ]);
    }

    /**
     * @Route("/invitations/{id}/delete", name="invitations_delete")
     */
    public function deleteInvitation(Invitation $invitation): Response
    {
        $this->entityManager->remove($invitation);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_user_invitations_list');
    }

    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function delete(Admin $admin): Response
    {
        $this->entityManager->remove($admin);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_user_list');
    }

    /**
     * @Route("/invite", name="invite", methods={"GET", "POST"})
     */
    public function invite(Request $request, InvitationManager $invitationManager): Response
    {
        $form = $this
            ->createForm(InviteType::class)
            ->handleRequest($request)
        ;

        if ($form->isSubmitted() && $form->isValid()) {
            $invitationModel = $form->getData();
            $invitationManager->invite($invitationModel->email, $invitationModel->roles);

            return $this->redirectToRoute('admin_user_invitations_list');
        }

        return $this->render('admin/user/invite.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Admin $admin, Request $request): Response
    {
        $form = $this->createForm(UserRolesType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
