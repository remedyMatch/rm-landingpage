<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Repository\FaqSectionRepository;
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
    private $faqSectionRepository;

    public function __construct(
        PartnerRepository $partnerRepository,
        MentionRepository $mentionRepository,
        FaqSectionRepository $faqSectionRepository
    ) {
        $this->partnerRepository = $partnerRepository;
        $this->mentionRepository = $mentionRepository;
        $this->faqSectionRepository = $faqSectionRepository;
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

    /**
     * @Route("/faq", name="faq", methods={"GET"})
     */
    public function faq(Request $request): Response
    {
        return $this->render('web/faq/faq.html.twig', [
            'faqSections' => $this->faqSectionRepository->findAll(),
        ]);
    }
}
