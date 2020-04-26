<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class StartController extends AbstractController
{
    /**
     * @Route("/start", name="start", methods={"GET"})
     */
    public function __invoke(): RedirectResponse
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_user_list');
        }

        if ($this->isGranted('ROLE_DECIDER')) {
            return $this->redirectToRoute('admin_registration_unreviewed_list');
        }

        return $this->redirectToRoute('admin_partners_list');
    }
}