<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\FaqEntry;
use App\Entity\FaqSection;
use App\Form\Admin\FaqEntryType;
use App\Form\Admin\FaqSectionType;
use App\Repository\FaqSectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/faq", name="faq_")
 *
 * @IsGranted("ROLE_MARKETING")
 */
final class FaqController extends AbstractController
{
    private $faqSectionRepository;
    private $entityManager;

    public function __construct(FaqSectionRepository $faqSectionRepository, EntityManagerInterface $entityManager)
    {
        $this->faqSectionRepository = $faqSectionRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/sections", name="section_list", methods={"GET"})
     */
    public function list(): Response
    {
        $faqSections = $this->faqSectionRepository->findAll();

        return $this->render('admin/faq/section/list.html.twig', [
            'faqSections' => $faqSections,
        ]);
    }

    /**
     * @Route("/sections/{id}/edit", name="section_edit", methods={"GET", "POST"})
     */
    public function edit(FaqSection $faqSection, Request $request): Response
    {
        $form = $this->createForm(FaqSectionType::class, $faqSection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($faqSection);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_faq_section_list');
        }

        return $this->render('admin/faq/section/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sections/create", name="section_create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $faqSection = new FaqSection();

        return $this->edit($faqSection, $request);
    }

    /**
     * @Route("/sections/{id}/delete", name="section_delete", methods={"GET"})
     */
    public function delete(FaqSection $faqSection): Response
    {
        $this->entityManager->remove($faqSection);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_faq_section_list');
    }

    /**
     * @Route("/sections/{id}/faq-entry/create", name="entry_add", methods={"GET", "POST"})
     */
    public function addFaqEntry(FaqSection $faqSection, Request $request): Response
    {
        $faqEntry = new FaqEntry();
        $faqEntry->setFaqSection($faqSection);
        $faqSection->addFaqEntry($faqEntry);

        return $this->editFaqEntry($faqEntry, $request);
    }

    /**
     * @Route("/entry/{id}/edit", name="entry_edit", methods={"GET", "POST"})
     */
    public function editFaqEntry(FaqEntry $faqEntry, Request $request): Response
    {
        $form = $this->createForm(FaqEntryType::class, $faqEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($faqEntry);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_faq_section_list');
        }

        return $this->render('admin/faq/entry/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/entry/{id}/delete", name="entry_delete", methods={"GET"})
     */
    public function deleteFaqEntry(FaqEntry $faqEntry): Response
    {
        $this->entityManager->remove($faqEntry);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_faq_section_list');
    }
}
