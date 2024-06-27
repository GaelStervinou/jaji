<?php

namespace App\Repository;

use App\Entity\DiagnosticMentalHealth;
use App\Entity\DiagnosticRisks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiagnosticMentalHealth>
 */
class DiagnosticMentalHealthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiagnosticMentalHealth::class);
    }


    /**
     * Return an array of diagnostics mental health last created and get the diagnostickRisk for this patient with the same created date
     */
    public function findListDiagnosticMentalHealth($diagnosticId): array
    {
        $current = $this->createQueryBuilder('dmh')
            ->select('dmh')
            ->where('dmh.id = :diagnosticId')
            ->setParameter('diagnosticId', $diagnosticId)
            ->getQuery()
            ->getResult();


        $last = $this->createQueryBuilder('dmh')
            ->select('dmh')
            ->where('dmh.patient = :patientId')
            ->andWhere('dmh.createdAt < :createdAt')
            ->setParameter('patientId', $current[0]->getPatient()->getId())
            ->setParameter('createdAt', $current[0]->getCreatedAt())
            ->orderBy('dmh.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        $next = $this->createQueryBuilder('dmh')
            ->select('dmh')
            ->where('dmh.patient = :patientId')
            ->andWhere('dmh.createdAt > :createdAt')
            ->setParameter('patientId', $current[0]->getPatient()->getId())
            ->setParameter('createdAt', $current[0]->getCreatedAt())
            ->orderBy('dmh.createdAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return ['current' => $current, 'last' => $last, 'next' => $next];
    }

    public function findDatesAndValuesByPatient($patientId): array
    {
        $results = $this->createQueryBuilder('dmh')
            ->select('dmh.createdAt', 'dmh.value')
            ->where('dmh.patient = :patient')
            ->setParameter('patient', $patientId)
            ->orderBy('dmh.createdAt', 'ASC')
            ->getQuery()
            ->getResult();

        $dates = array_map(function($item) {
            return $item['createdAt'];
        }, $results);

        $values = array_map(function($item) {
            return $item['value'];
        }, $results);

        return ['dates' => $dates, 'values' => $values];
    }

}