<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\StaticData\Mentions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PressController extends AbstractController
{
    private static function date_compare($element1, $element2): int
    {
        $datetime1 = strtotime($element1['date']);
        $datetime2 = strtotime($element2['date']);

        return $datetime2 - $datetime1;
    }

    /**
     * @Route("/presse", name="press", methods={"GET"})
     */
    public function press(): Response
    {
        $mentions = Mentions::DATA;
        usort($mentions, [$this, 'date_compare']);

        return $this->render('press/presse.html.twig', ['mentions' => $mentions]);
    }
}
