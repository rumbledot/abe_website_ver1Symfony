<?php

namespace App\Repository;

use App\Entity\TodoTextcls;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TodoTextcls|null find($id, $lockMode = null, $lockVersion = null)
 * @method TodoTextcls|null findOneBy(array $criteria, array $orderBy = null)
 * @method TodoTextcls[]    findAll()
 * @method TodoTextcls[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoTextclsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodoTextcls::class);
    }

    // /**
    //  * @return TodoTextcls[] Returns an array of TodoTextcls objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TodoTextcls
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
