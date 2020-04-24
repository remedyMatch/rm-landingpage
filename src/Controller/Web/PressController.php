<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\StaticData\Mentions;
use App\Util\Sorting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PressController extends AbstractController
{
    /**
     * @Route({
     *     "/press",
     *     "de": "/presse"
     * }, name="press", methods={"GET"})
     */
    public function press(): Response
    {
        $mentions = Mentions::DATA;
        usort($mentions, [Sorting::class, 'dateCompareArrays']);

        return $this->render('web/press/press.html.twig', ['mentions' => $mentions]);
    }
}
