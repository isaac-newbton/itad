<?php

namespace App\Repository;

use App\Doctrine\UuidEncoder;
use App\Entity\Adulterant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Adulterant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adulterant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adulterant[]    findAll()
 * @method Adulterant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdulterantRepository extends ServiceEntityRepository
{
    use UuidFinderTrait;

    public function __construct(ManagerRegistry $registry, UuidEncoder $encoder)
    {
        $this->uuidEncoder = $encoder;
        parent::__construct($registry, Adulterant::class);
    }

    public function findAll(){
        return $this->findBy([], ['name'=>'ASC']);
    }

    public function findByFirstLetter($letter){
        return $this->createQueryBuilder('a')
            ->where('a.name LIKE :letter')
            ->orderBy('a.name', 'ASC')
            ->setParameter('letter', $letter . '%')
            ->getQuery()
            ->getResult()
        ;
    }

    public function search(string $term){
        return $this->createQueryBuilder('a')
            ->where('a.name LIKE :term')
            ->orWhere('a.synonyms LIKE :term')
            ->orWhere('a.spanishName LIKE :term')
            ->orWhere('a.drugClass LIKE :term')
            ->orWhere('a.occurrenceUsage LIKE :term')
            ->orWhere('a.physiologicalEffect LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('a.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Adulterant[] Returns an array of Adulterant objects
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
    public function findOneBySomeField($value): ?Adulterant
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
