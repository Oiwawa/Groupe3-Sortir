<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Filters\NameFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Campus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campus[]    findAll()
 * @method Campus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campus::class);
    }


    /**
     * @param NameFilter $campusFilter
     * @return Campus[]
     */
    public function findName(NameFilter $campusFilter): array
    {
        $query = $this->createQueryBuilder('campus')
        ->andWhere('campus.name LIKE :text')
        ->setParameter('text',"%{$campusFilter->text}%" );

        return $query->getQuery()->getResult();
    }
}
