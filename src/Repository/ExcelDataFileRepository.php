<?php

namespace App\Repository;

use App\Doctrine\UuidEncoder;
use App\Entity\ExcelDataFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExcelDataFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExcelDataFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExcelDataFile[]    findAll()
 * @method ExcelDataFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExcelDataFileRepository extends ServiceEntityRepository
{
    use UuidFinderTrait;
    public function __construct(ManagerRegistry $registry, UuidEncoder $encoder)
    {
        $this->uuidEncoder = $encoder;
        parent::__construct($registry, ExcelDataFile::class);
    }

    // /**
    //  * @return ExcelDataFile[] Returns an array of ExcelDataFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExcelDataFile
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
