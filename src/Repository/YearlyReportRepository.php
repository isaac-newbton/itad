<?php

namespace App\Repository;

use App\Doctrine\UuidEncoder;
use App\Entity\YearlyReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method YearlyReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method YearlyReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method YearlyReport[]    findAll()
 * @method YearlyReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YearlyReportRepository extends ServiceEntityRepository
{
    use UuidFinderTrait;
    public function __construct(ManagerRegistry $registry, UuidEncoder $encoder)
    {
        $this->uuidEncoder = $encoder;
        parent::__construct($registry, YearlyReport::class);
    }

    // /**
    //  * @return YearlyReport[] Returns an array of YearlyReport objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('y.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?YearlyReport
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
