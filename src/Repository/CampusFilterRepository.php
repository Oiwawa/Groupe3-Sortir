<?php

namespace App\Repository;

use App\Entity\CampusFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CampusFilter|null find($id, $lockMode = null, $lockVersion = null)
 * @method CampusFilter|null findOneBy(array $criteria, array $orderBy = null)
 * @method CampusFilter[]    findAll()
 * @method CampusFilter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampusFilterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CampusFilter::class);
    }

    // /**
    //  * @return CampusFilter[] Returns an array of CampusFilter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CampusFilter
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
