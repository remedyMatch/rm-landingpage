<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Partner;
use App\Form\Admin\PartnerType;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/partners", name="partners_")
 *
 * @IsGranted("ROLE_MARKETING")
 */
class PartnerController extends AbstractController
{
    private $partnerRepository;
    private $entityManager;

    public function __construct(PartnerRepository $partnerRepository, EntityManagerInterface $entityManager)
    {
        $this->partnerRepository = $partnerRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="list", methods={"GET"})
     */
    public function list(): Response
    {
        $partners = $this->partnerRepository->findAllOrdered();

        return $this->render('admin/partner/list.html.twig', [
            'partners' => $partners,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Partner $partner, Request $request): Response
    {
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($partner);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_partners_list');
        }

        return $this->render('admin/partner/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $partner = new Partner();

        return $this->edit($partner, $request);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"GET"})
     */
    public function delete(Partner $partner): Response
    {
        $this->entityManager->remove($partner);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_partners_list');
    }
}
