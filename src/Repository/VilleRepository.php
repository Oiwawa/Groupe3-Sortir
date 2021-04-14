<?php

namespace App\Repository;

use App\Entity\Ville;
use App\Filters\NameFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ville|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ville|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ville[]    findAll()
 * @method Ville[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ville::class);
    }
    /**
     * @param NameFilter $villeFilter
     * @return Ville[]
     */
    public function findName(NameFilter $villeFilter): array
    {
        $query = $this->createQueryBuilder('ville')
            ->andWhere('ville.name LIKE :text')
            ->setParameter('text',"%{$villeFilter->text}%" );

        return $query->getQuery()->getResult();
    }

}
