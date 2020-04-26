<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Mention;
use App\Form\Admin\MentionType;
use App\Repository\MentionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mentions", name="mentions_")
 *
 * @IsGranted("ROLE_MARKETING")
 */
class MentionController extends AbstractController
{
    /**
     * @var MentionRepository
     */
    private $mentionRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(MentionRepository $mentionRepository, EntityManagerInterface $entityManager)
    {
        $this->mentionRepository = $mentionRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="list", methods={"GET"})
     */
    public function list(): Response
    {
        $mentions = $this->mentionRepository->findAllOrderedWithLimit(false);

        return $this->render('admin/mention/list.html.twig', [
            'mentions' => $mentions,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Mention $mention, Request $request): Response
    {
        $form = $this->createForm(MentionType::class, $mention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($mention);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_mentions_list');
        }

        return $this->render('admin/mention/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $mention = new Mention();

        return $this->edit($mention, $request);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"GET"})
     */
    public function delete(Mention $mention): Response
    {
        $this->entityManager->remove($mention);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_mentions_list');
    }
}
