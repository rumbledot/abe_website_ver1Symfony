<?php

namespace App\Repository;

use App\Entity\ListText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListText|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListText|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListText[]    findAll()
 * @method ListText[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListTextRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListText::class);
    }

    // /**
    //  * @return ListText[] Returns an array of ListText objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ListText
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
