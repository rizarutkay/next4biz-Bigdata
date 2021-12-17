<?php

namespace App\Repository;

use App\Entity\Email;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Email|null find($id, $lockMode = null, $lockVersion = null)
 * @method Email|null findOneBy(array $criteria, array $orderBy = null)
 * @method Email[]    findAll()
 * @method Email[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Email::class);
    }

    /**
     * @param integer $startYear
     * @param integer $endYear
     * 
     * @return array
     */
    public function getYearlyEmailCount(int $startYear, int $endYear): array
    {
        $params = [
            'startYear' => $startYear,
            'endYear' => $endYear,
        ];

        return $this->createQueryBuilder('e')
        ->select('COUNT(e.emailId) AS email', 'e.year')
        ->where('e.year >= :startYear')
        ->andWhere('e.year <= :endYear')
        ->setParameters($params)
        ->groupBy('e.year')
        ->getQuery()
        ->getResult();
    }

    /**
     * @param array $queryDates
     * 
     * @return iterable
     */
    public function getMonthlyEmailCount(array $queryDates): iterable
    {
        $queryBuilder = $this->createQueryBuilder('e')
        ->select('COUNT(e.emailId) AS email', "CONCAT(e.year, '-' ,e.month) AS date",);

        foreach ($queryDates as $year=>$months) {
            $queryBuilder->orWhere(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('e.year', $year),
                    $queryBuilder->expr()->in('e.month', $months),
                )
            );
        }

        return $queryBuilder->groupBy('e.year','e.month')
        ->getQuery()
        ->toIterable();
    }

    /**
     * @param array $queryDates
     * 
     * @return iterable
     */
    public function getWeeklyEmailCount(array $queryDates): iterable
    {
        $queryBuilder = $this->createQueryBuilder('e')
        ->select('COUNT(e.emailId) AS email', "CONCAT(e.year, 'W' ,e.week) AS date",);

        foreach ($queryDates as $year=>$weeks) {
            $weeks = implode(',', $weeks);
            $queryBuilder->orWhere(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('e.year', $year),
                    $queryBuilder->expr()->in('e.week', $weeks),
                )
            );
        }
        
        return $queryBuilder->groupBy('e.year','e.week')
        ->getQuery()
        ->toIterable();
    }

    /**
     * @param array $queryDates
     * 
     * @return iterable
     */
    public function getDailyEmailCount(array $queryDates): iterable
    {
        $queryBuilder = $this->createQueryBuilder('e')
        ->select('COUNT(e.emailId) AS email', "CONCAT(e.year, '-', e.month, '-', e.day) AS date");

        foreach ($queryDates as $year=>$months) {
            foreach ($months as $month=>$days) {
                $days = implode(',', $days);
                $queryBuilder->orWhere(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq('e.year', $year),
                        $queryBuilder->expr()->eq('e.month', $month),
                        $queryBuilder->expr()->in('e.day', $days),
                    )
                );
            }
        }
        
        return $queryBuilder->groupBy('e.year', 'e.month', 'e.day')
        ->getQuery()
        ->toIterable();
    }
}
