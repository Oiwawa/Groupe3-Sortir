<?php

namespace App\Repository;

use App\Entity\Event;
use App\Filters\Filters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);

    }

    /**
     * @param Filters $filters
     * @param UserInterface $user
     * @return Event[]
     */
    public function findSearch(Filters $filters, UserInterface $user): array
    {

        $query = $this->createQueryBuilder('event')
            ->select('event', 'campus')
            ->join('event.campus', 'campus')

        ;

        if (!empty($filters->text)) {
            $query = $query
                ->andWhere('event.name LIKE :text')
                ->setParameter('text', "%{$filters->text}%");
        }
        if (!empty($filters->campus)) {
            $query = $query
                ->andWhere('campus IN (:campus)')
                ->setParameter('campus', $filters->campus);
        }
        if(!empty($filters->organizer)){
            $query = $query
                ->andWhere('event.organizer = :organizer')
                ->setParameter('organizer', $user);
        }

//        if(!empty($filters->subscribed)){
//            $query = $query
//                ->andWhere('event.subscribers = :subscribers')
//                ->setParameter('user', $user);
//        }

        if (!empty($filters->dateStart)) {
            $query = $query
                ->andWhere('event.eventDate > (:dateStart)')
                ->setParameter('dateStart', $filters->dateStart);
        }
        if (!empty($filters->dateEnd)) {
            $query = $query
                ->andWhere('event.eventDate < (:dateEnd)')
                ->setParameter('dateEnd', $filters->dateEnd);
        }
        if(!empty($filters->passedEvents)){
            $query = $query
                ->andWhere('event.state = 4');
        }
        return $query->getQuery()->getResult();
    }

}
