<?php

namespace App\Repository;

use App\Entity\DiagnosticRisks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiagnosticRisks>
 */
class DiagnosticRisksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiagnosticRisks::class);
    }

    public function findLastDiagnosticRisk($diagnoticId): array
    {
        return $this->createQueryBuilder('dr')
            ->select('dr')
            ->where('dr.id = :diagnoticId')
            ->setParameter('diagnoticId', $diagnoticId)
            ->orderBy('dr.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function findDatesAndValuesByPatient($patientId): array
    {
        $results = $this->createQueryBuilder('dr')
            ->select('dr.createdAt', 'dr.value')
            ->where('dr.patient = :patient')
            ->setParameter('patient', $patientId)
            ->orderBy('dr.createdAt', 'ASC')
            ->getQuery()
            ->getResult();

        $dates = array_map(function ($item) {
            return $item['createdAt'];
        }, $results);

        $values = array_map(function ($item) {
            return $item['value'];
        }, $results);

        return ['dates' => $dates, 'values' => $values];
    }
}