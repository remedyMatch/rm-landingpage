<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\DynamicTerm;
use App\Form\Admin\DynamicTermModel;
use App\Form\Admin\DynamicTermType;
use App\Repository\DynamicTermRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dynamic-terms", name="dynamic-terms_")
 *
 * @IsGranted("ROLE_MARKETING")
 */
class DynamicTermsController extends AbstractController
{
    /**
     * @var DynamicTermRepository
     */
    private $dynamicTermRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(DynamicTermRepository $dynamicTermRepository, EntityManagerInterface $entityManager)
    {
        $this->dynamicTermRepository = $dynamicTermRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="list", methods={"GET"})
     */
    public function list(): Response
    {
        return $this->render('admin/dynamic-terms/list.html.twig', [
            'dynamicTerms' => $this->dynamicTermRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(DynamicTerm $dynamicTerm, Request $request): Response
    {
        $form = $this
            ->createForm(DynamicTermType::class, DynamicTermModel::fromEntity($dynamicTerm))
            ->handleRequest($request)
        ;

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var DynamicTermModel $dynamicTermModel */
            $dynamicTermModel = $form->getData();
            $dynamicTerm->update($dynamicTermModel->placeholder, $dynamicTermModel->value);

            $this->entityManager->persist($dynamicTerm);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_dynamic-terms_list');
        }

        return $this->render('admin/dynamic-terms/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $dynamicTerm = new DynamicTerm('', '');

        return $this->edit($dynamicTerm, $request);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"GET"})
     */
    public function delete(DynamicTerm $dynamicTerm): Response
    {
        $this->entityManager->remove($dynamicTerm);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_dynamic-terms_list');
    }
}
