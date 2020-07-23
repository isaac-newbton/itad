<?php

namespace App\Repository;

use App\Doctrine\UuidEncoder;
use App\Entity\ReportLineItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportLineItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportLineItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportLineItem[]    findAll()
 * @method ReportLineItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportLineItemRepository extends ServiceEntityRepository
{
    use UuidFinderTrait;
    public function __construct(ManagerRegistry $registry, UuidEncoder $encoder)
    {
        $this->uuidEncoder = $encoder;
        parent::__construct($registry, ReportLineItem::class);
    }

    // /**
    //  * @return ReportLineItem[] Returns an array of ReportLineItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReportLineItem
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
