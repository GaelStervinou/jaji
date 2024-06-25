<?php

namespace App\Controller;

use App\Entity\Weights;
use App\Form\WeightsType;
use App\Repository\WeightsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/weights')]
class WeightsController extends AbstractController
{
    #[Route('/', name: 'app_weights_index', methods: ['GET'])]
    public function index(WeightsRepository $weightsRepository): Response
    {
        return $this->render('weights/index.html.twig', [
            'weights' => $weightsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_weights_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $weight = new Weights();
        $form = $this->createForm(WeightsType::class, $weight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($weight);
            $entityManager->flush();

            return $this->redirectToRoute('app_weights_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weights/new.html.twig', [
            'weight' => $weight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weights_show', methods: ['GET'])]
    public function show(Weights $weight): Response
    {
        return $this->render('weights/show.html.twig', [
            'weight' => $weight,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_weights_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Weights $weight, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WeightsType::class, $weight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_weights_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weights/edit.html.twig', [
            'weight' => $weight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weights_delete', methods: ['POST'])]
    public function delete(Request $request, Weights $weight, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weight->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($weight);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_weights_index', [], Response::HTTP_SEE_OTHER);
    }
}
