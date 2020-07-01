<?php

namespace App\Repository;

use App\Entity\BlogMap;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogMap|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogMap|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogMap[]    findAll()
 * @method BlogMap[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogMapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogMap::class);
    }

    // /**
    //  * @return BlogMap[] Returns an array of BlogMap objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BlogMap
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
