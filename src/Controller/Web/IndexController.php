<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Repository\MentionRepository;
use App\Repository\PartnerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class IndexController extends AbstractController
{
    private $partnerRepository;
    private $mentionRepository;

    public function __construct(PartnerRepository $partnerRepository, MentionRepository $mentionRepository)
    {
        $this->partnerRepository = $partnerRepository;
        $this->mentionRepository = $mentionRepository;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $isGerman = 'de' === $request->getLocale();

        return $this->render('web/index/index.html.twig', [
            'preregister' => $request->get('registered'),
            'partners' => $this->partnerRepository->findAllOrdered(),
            'mentions' => $this->mentionRepository->findAllOrderedWithLimit(!$isGerman, 8),
            'emailSent' => $request->get('mailSent'),
        ]);
    }
}
