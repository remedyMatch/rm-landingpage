<?php

declare(strict_types=1);

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobsController extends AbstractController
{
    /**
     * @Route("/offene-positionen", name="jobs", methods={"GET"})
     *
     * @return Response
     */
    public function jobs(Request $request)
    {
        return $this->render('jobs/jobs.html.twig', ['emailSent' => $request->get('mailSent')]);
    }
}
