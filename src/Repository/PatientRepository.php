<?php

namespace App\Repository;

use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patient>
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    public function indexSearch(
        int $page = 1,
        ?string $search = null,
        ?string $lastDiagnosticRisksSortBy = null,
        ?string $lastDiagnosticMentalHealthSortBy = null,
    ): array
    {
        $query = $this->createQueryBuilder('p');
        if ($search) {
            $query->andWhere("p.firstname LIKE :search OR p.lastname LIKE :search OR CONCAT(p.firstname, ' ', p.lastname) LIKE :search OR p.ipp LIKE :search OR p.email LIKE :search")
                ->setParameter('search', "%$search%");
        }

        if ($lastDiagnosticRisksSortBy) {
            $query->orderBy('p.lastDiagnosticRisks', $lastDiagnosticRisksSortBy);
        } elseif ($lastDiagnosticMentalHealthSortBy) {
            $query->orderBy('p.lastDiagnosticMentalHealth', $lastDiagnosticMentalHealthSortBy);
        }

        $query->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10);

        return [
            'results' => $query->getQuery()->getResult(),
            'count' => $query->select('COUNT(p.id)')->getQuery()->getSingleScalarResult(),
        ];
    }

    //    /**
    //     * @return Patient[] Returns an array of Patient objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Patient
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
