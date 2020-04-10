<?php

declare(strict_types=1);

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class JobsController extends AbstractController
{
    /**
     * @Route("/offene-positionen", name="jobs", methods={"GET"})
     */
    public function jobs(Request $request): Response
    {
        return $this->render('web/jobs/jobs.html.twig', ['emailSent' => $request->get('mailSent')]);
    }
}
