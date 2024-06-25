<?php

namespace App\Controller;

use App\Entity\DiagnosticMentalHealth;
use App\Form\DiagnosticMentalHealthType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/diagnostic/mental-health')]
class DiagnosticMentalHealthController extends AbstractController
{
    #[Route('/', name: 'app_diagnostic_mental_health_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('diagnostic_mental_health/index.html.twig', [
            'controller_name' => 'DiagnosticMentalHealthController',
        ]);
    }

    #[Route('/new', name: 'app_diagnostic_mental_health_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $diagnosticMentalHealth = new DiagnosticMentalHealth();
        $form = $this->createForm(DiagnosticMentalHealthType::class, $diagnosticMentalHealth);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($diagnosticMentalHealth);
            $entityManager->flush();

            return $this->redirectToRoute('app_diagnostic_mental_health_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('diagnostic_mental_health/new.html.twig', [
            'diagnostic_mental_health' => $diagnosticMentalHealth,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_diagnostic_mental_health_show', methods: ['GET'])]
    public function show(DiagnosticMentalHealth $diagnosticMentalHealth): Response
    {
        return $this->render('diagnostic_mental_health/show.html.twig', [
            'diagnostic_mental_health' => $diagnosticMentalHealth,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_diagnostic_mental_health_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DiagnosticMentalHealth $diagnosticMentalHealth, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DiagnosticMentalHealthType::class, $diagnosticMentalHealth);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_diagnostic_mental_health_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('diagnostic_mental_health/edit.html.twig', [
            'diagnostic_mental_health' => $diagnosticMentalHealth,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_diagnostic_mental_health_delete', methods: ['POST'])]
    public function delete(Request $request, DiagnosticMentalHealth $diagnosticMentalHealth, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$diagnosticMentalHealth->getId(), $request->request->get('_token'))) {
            $entityManager->remove($diagnosticMentalHealth);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_diagnostic_mental_health_index', [], Response::HTTP_SEE_OTHER);
    }

}