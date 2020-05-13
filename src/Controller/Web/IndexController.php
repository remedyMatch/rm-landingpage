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
    /**
     * @Route("/faq", name="faq", methods={"GET"})
     */
    public function faq(Request $request): Response
    {
        $isGerman = 'de' === $request->getLocale();
        $faqs =[
            [
                'question' => 'Wie melde ich mich an?',
                'answer' => 'Gehen Sie auf "Jetzt Spenden", dort können Sie sich einlogen'
            ],
            [
                'question' => 'Wie melde ich registriere ich mich?',
                'answer' => 'Klicken Sie auf der Website auf "Jetzt registrieren", dort können Sie sich registrieren.'
            ],
        ];
        return $this->render('web/faq/faq.html.twig',[
            'faqscat1' => $faqs,
            'faqscat2' => $faqs,
            'faqscat3' => $faqs,
            'faqscat4' => $faqs,
            'faqscat5' => $faqs
        ]);
    }
}
