<?php

namespace App\Controller;

use App\Entity\DiagnosticRisks;
use App\Form\DiagnosticRisksType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/diagnostic/risks')]
class DiagnosticRisksController extends AbstractController
{
    #[Route('/', name: 'app_diagnostic_risks_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('diagnostic_risks/index.html.twig', [
            'controller_name' => 'DiagnosticRisksController',
        ]);
    }

    #[Route('/new', name: 'app_diagnostic_risks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $diagnosticRisks = new DiagnosticRisks();
        $form = $this->createForm(DiagnosticRisksType::class, $diagnosticRisks);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($diagnosticRisks);
            $entityManager->flush();

            return $this->redirectToRoute('app_diagnostic_risks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('diagnostic_risks/new.html.twig', [
            'diagnostic_risks' => $diagnosticRisks,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_diagnostic_risks_show', methods: ['GET'])]
    public function show(DiagnosticRisks $diagnosticRisks): Response
    {
        return $this->render('diagnostic_risks/show.html.twig', [
            'diagnostic_risks' => $diagnosticRisks,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_diagnostic_risks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DiagnosticRisks $diagnosticRisks, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DiagnosticRisksType::class, $diagnosticRisks);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_diagnostic_risks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('diagnostic_risks/edit.html.twig', [
            'diagnostic_risks' => $diagnosticRisks,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_diagnostic_risks_delete', methods: ['POST'])]
    public function delete(Request $request, DiagnosticRisks $diagnosticRisks, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$diagnosticRisks->getId(), $request->request->get('_token'))) {
            $entityManager->remove($diagnosticRisks);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_diagnostic_risks_index', [], Response::HTTP_SEE_OTHER);
    }

}