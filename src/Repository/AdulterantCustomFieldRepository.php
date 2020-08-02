<?php

namespace App\Repository;

use App\Doctrine\UuidEncoder;
use App\Entity\AdulterantCustomField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdulterantCustomField|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdulterantCustomField|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdulterantCustomField[]    findAll()
 * @method AdulterantCustomField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdulterantCustomFieldRepository extends ServiceEntityRepository
{
    use UuidFinderTrait;

    public function __construct(ManagerRegistry $registry, UuidEncoder $encoder)
    {
        $this->uuidEncoder = $encoder;
        parent::__construct($registry, AdulterantCustomField::class);
    }

    // /**
    //  * @return AdulterantCustomField[] Returns an array of AdulterantCustomField objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdulterantCustomField
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
