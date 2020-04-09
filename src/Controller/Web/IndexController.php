<?php

namespace App\Controller\Web;

use App\StaticData\Mentions;
use App\StaticData\Partners;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $mentions = Mentions::DATA;
        usort($mentions, [$this, 'date_compare']);

        return $this->render('index/index.html.twig', [
            'preregister' => $request->get('registered'),
            'partners' => Partners::DATA,
            'mentions' => $mentions,
            'emailSent' => $request->get('mailSent'),
        ]);
    }
}
