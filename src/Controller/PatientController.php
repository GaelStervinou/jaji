<?php

namespace App\Controller;

use App\Entity\DiagnosticMentalHealth;
use App\Entity\Patient;
use App\Form\PatientType;
use App\Repository\DiagnosticMentalHealthRepository;
use App\Repository\DiagnosticRisksRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/patient')]
class PatientController extends AbstractController
{
    #[Route('/', name: 'app_patient_index', methods: ['GET'])]
    public function index(Request $request, PatientRepository $patientRepository): Response
    {
        //pagination marche pas
        $page = $request->query->get('page') ?? 1;
        $search = $request->query->get('search');

        $lastDiagnosticRisksSortBy = $request->query->get('lastDiagnosticRisksSortBy');
        $lastDiagnosticMentalHealthSortBy = $request->query->get('lastDiagnosticMentalHealthSortBy');

        if ($lastDiagnosticRisksSortBy && !in_array($lastDiagnosticRisksSortBy, ['ASC', 'DESC'])) {
            $lastDiagnosticRisksSortBy = null;
        }
        if ($lastDiagnosticMentalHealthSortBy && !in_array($lastDiagnosticMentalHealthSortBy, ['ASC', 'DESC'])) {
            $lastDiagnosticMentalHealthSortBy = null;
        }
        $patients = $patientRepository->indexSearch($page, $search, $lastDiagnosticRisksSortBy, $lastDiagnosticMentalHealthSortBy);
        return $this->render('patient/index.html.twig', [
            'patients' => $patients['results'],
            'total' => $patients['count'],
        ]);
    }

    #[Route('/new', name: 'app_patient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($patient);
            $entityManager->flush();

            return $this->redirectToRoute('app_patient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('patient/new.html.twig', [
            'patient' => $patient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_patient_show', methods: ['GET'])]
    public function show(Patient $patient): Response
    {
        return $this->render('patient/show.html.twig', [
            'patient' => $patient,
        ]);
    }

    #[Route('/{id}/{diagnosticId}', name: 'app_patient_show_details', methods: ['GET'])]
    public function showPatient(Patient $patient, DiagnosticMentalHealth $diagnosticId, DiagnosticMentalHealthRepository $diagnosticMentalHealthRepository, DiagnosticRisksRepository $diagnosticRisksRepository): Response
    {
        $diagnosticMentalHealthRepository = $diagnosticMentalHealthRepository->findListDiagnosticMentalHealth(['id' => $diagnosticId->getId()]);
        $diagnosticRisk = $diagnosticRisksRepository->findOneBy(['patient' => $patient->getId(), 'createdAt' => $diagnosticId->getCreatedAt()]);
        $lastDiagnosticRisk = $diagnosticRisksRepository->findLastDiagnosticRisk($diagnosticId->getId());
        return $this->render('patient/show.html.twig', [
            'patient' => $patient,
            'diagnosticMentalHealth' => $diagnosticMentalHealthRepository,
            'diagnosticRisk' => $diagnosticRisk,
            'lastDiagnosticRisk' => $lastDiagnosticRisk,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_patient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Patient $patient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_patient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('patient/edit.html.twig', [
            'patient' => $patient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_patient_delete', methods: ['POST'])]
    public function delete(Request $request, Patient $patient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$patient->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($patient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_patient_index', [], Response::HTTP_SEE_OTHER);
    }
}
