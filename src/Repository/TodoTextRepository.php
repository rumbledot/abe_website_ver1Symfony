<?php

namespace App\Repository;

use App\Entity\TodoText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TodoText|null find($id, $lockMode = null, $lockVersion = null)
 * @method TodoText|null findOneBy(array $criteria, array $orderBy = null)
 * @method TodoText[]    findAll()
 * @method TodoText[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoTextRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodoText::class);
    }

    // /**
    //  * @return TodoText[] Returns an array of TodoText objects
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
    public function findOneBySomeField($value): ?TodoText
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
