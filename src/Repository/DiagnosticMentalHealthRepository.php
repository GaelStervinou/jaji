<?php

namespace App\Repository;

use App\Entity\DiagnosticMentalHealth;
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

}