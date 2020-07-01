<?php

namespace App\Repository;

use App\Entity\ListMap;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListMap|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListMap|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListMap[]    findAll()
 * @method ListMap[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListMapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListMap::class);
    }

    // /**
    //  * @return ListMap[] Returns an array of ListMap objects
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
    public function findOneBySomeField($value): ?ListMap
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
