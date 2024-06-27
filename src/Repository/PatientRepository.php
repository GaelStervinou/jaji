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
        int     $page = 1,
        ?string $search = null,
        ?string $lastDiagnosticRisksSortBy = null,
        ?string $lastDiagnosticMentalHealthSortBy = null,
    ): array
    {
        $this->createQueryBuilder('p')
            ->select('p');

        $searchQuery = '';
        if ($search) {
            $searchQuery = sprintf(" WHERE (p.firstname LIKE '%s' OR p.lastname LIKE '%s' OR CONCAT(p.firstname, ' ', p.lastname) LIKE '%s' OR p.ipp LIKE '%s' OR p.email LIKE '%s')", '%' . $search . '%', '%' . $search . '%', '%' . $search . '%', '%' . $search . '%', '%' . $search . '%');
        }
        if ($lastDiagnosticRisksSortBy) {
            $conn = $this->getEntityManager()->getConnection();
            $sql = sprintf(
                "SELECT p.id, COUNT(*) OVER () AS total_count
FROM patient p
JOIN LATERAL (
         SELECT d1.*
         FROM diagnostic_risks d1
         WHERE d1.id = (
             SELECT d2.id
             FROM diagnostic_risks d2
             WHERE d2.patient_id = p.id
             ORDER BY d2.created_at DESC
             LIMIT 1
         )
     ) d ON d.patient_id = p.id
%s
ORDER BY d.value %s
OFFSET %d
LIMIT %d;", $searchQuery, $lastDiagnosticRisksSortBy, ($page - 1) * 10, 10);
            $stmt = $conn->prepare($sql);

            $result = $stmt->executeQuery()->fetchAll();
            if ($result === false) {
                throw new \Exception('Error while fetching data');
            }
            if (count($result) > 0) {
                $count = $result[ 0 ][ 'total_count' ];
            } else {
                $count = 0;
            }

            $patients = $this->findBy(['id' => array_map(fn($item) => $item[ 'id' ], $result)]);
            return [
                'results' => $patients,
                'count' => $count,
            ];
        } elseif ($lastDiagnosticMentalHealthSortBy) {
            $conn = $this->getEntityManager()->getConnection();
            $sql = sprintf(
                "SELECT p.id, COUNT(*) OVER () AS total_count
FROM patient p
JOIN LATERAL (
         SELECT d1.*
         FROM diagnostic_mental_health d1
         WHERE d1.id = (
             SELECT d2.id
             FROM diagnostic_mental_health d2
             WHERE d2.patient_id = p.id
             ORDER BY d2.created_at DESC
             LIMIT 1
         )
     ) d ON d.patient_id = p.id
%s
ORDER BY d.value %s
OFFSET %d
LIMIT %d;", $searchQuery, $lastDiagnosticMentalHealthSortBy, ($page - 1) * 10, 10);
            $stmt = $conn->prepare($sql);

            $result = $stmt->executeQuery()->fetchAll();
            if ($result === false) {
                throw new \Exception('Error while fetching data');
            }
            if (count($result) > 0) {
                $count = $result[ 0 ][ 'total_count' ];
            } else {
                $count = 0;
            }
            $patients = $this->findBy(['id' => array_map(fn($item) => $item[ 'id' ], $result)]);
            return [
                'results' => $patients,
                'count' => $count,
            ];
        }
        $conn = $this->getEntityManager()->getConnection();
        $sql = sprintf(
            "SELECT p.id, COUNT(*) OVER () AS total_count
FROM patient p
%s
OFFSET %d
LIMIT %d;",$searchQuery, ($page - 1) * 10, 10);

        $stmt = $conn->prepare($sql);

        $result = $stmt->executeQuery()->fetchAll();
        if ($result === false) {
            throw new \Exception('Error while fetching data');
        }
        if (count($result) > 0) {
            $count = $result[ 0 ][ 'total_count' ];
        } else {
            $count = 0;
        }

        $patients = $this->findBy(['id' => array_map(fn($item) => $item[ 'id' ], $result)]);
        return [
            'results' => $patients,
            'count' => $count,
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
