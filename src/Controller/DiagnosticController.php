<?php

namespace App\Controller;

use App\Entity\DiagnosticRisks;
use App\Entity\Patient;
use App\Event\PatientDataUpdatedEvent;
use App\Form\DiagnosticRisksType;
use App\Repository\DiagnosticRisksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/diagnostic')]
class DiagnosticController extends AbstractController
{
    #[Route('/generate/{patient}', name: 'app_diagnostic_generate', methods: ['GET'])]
    public function index(Patient $patient, EventDispatcherInterface $eventDispatcher): Response
    {
        $eventDispatcher->dispatch(new PatientDataUpdatedEvent($patient->getId()));

        return $this->redirectToRoute('app_patient_index', [], Response::HTTP_SEE_OTHER);
    }
}