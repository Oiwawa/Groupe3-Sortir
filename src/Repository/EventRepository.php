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

        //Récupère tous les événements
        $query = $this->createQueryBuilder('event')
            ->select('event', 'campus')
            ->join('event.campus', 'campus')

        ;
        //Si des filtres sont séléctionnés, afine la recherche
        //Test le champ de texte
        if (!empty($filters->text)) {
            $query = $query
                ->andWhere('event.name LIKE :text')
                ->setParameter('text', "%{$filters->text}%");
        }
        //Récupère les event lié au campus sélectionné
        if (!empty($filters->campus)) {
            $query = $query
                ->andWhere('campus IN (:campus)')
                ->setParameter('campus', $filters->campus);
        }
        //Récupère les event organisé par l'user connecté
        if(!empty($filters->organizer)){
            $query = $query
                ->andWhere('event.organizer = :organizer')
                ->setParameter('organizer', $user);
        }

        //Récupère les event entre la date de début sélectionné
        if (!empty($filters->dateStart)) {
            $query = $query
                ->andWhere('event.eventDate > (:dateStart)')
                ->setParameter('dateStart', $filters->dateStart);
        }
        //Et la date de fin sélectionné
        if (!empty($filters->dateEnd)) {
            $query = $query
                ->andWhere('event.eventDate < (:dateEnd)')
                ->setParameter('dateEnd', $filters->dateEnd);
        }
        //Récupère les event archivé
        if(!empty($filters->passedEvents)){
            $query = $query
                ->andWhere('event.state = 4');
        }
        //Renvoie des résultats
        return $query->getQuery()->getResult();
    }

}
