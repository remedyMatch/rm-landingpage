<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\StaticData\Mentions;
use App\StaticData\Partners;
use App\Util\Sorting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $mentions = Mentions::DATA;
        usort($mentions, [Sorting::class, 'prioCompareArrays']);

        return $this->render('web/index/index.html.twig', [
            'preregister' => $request->get('registered'),
            'partners' => Partners::DATA,
            'mentions' => $mentions,
            'emailSent' => $request->get('mailSent'),
        ]);
    }
}
