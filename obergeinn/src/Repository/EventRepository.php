<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Participation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

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
     * Search the events for a user connected filtered by a variable $title
     * 
     * @param $title the keyword informed into the search input
     * @param $userId the id of the user connected
     * @return Event[] Returns an array of Event objects
     */
    public function searchEventsOrganizedByTitle($title, $userId)
    {   
        $queryBuilder = $this->_em->createQueryBuilder()
           ->select('e')
           ->from(Event::class, 'e')
           ->innerJoin(User::class, 'u', Join::WITH, 'e.user = u')
           ->where('e.title LIKE :title')
           ->andWhere('u.id LIKE :user')
           ->setParameter(':title', "%$title%")
           ->setParameter(':user', "%$userId%");

        $query = $queryBuilder->getQuery();
        // dd($query);
        return $query->getResult();
    }

    /**
     * Search the events invited for a user connected filtered by a variable $title
     * 
     * @param $title the keyword informed into the search input
     * @param $userId the id of the user connected
     * @return Event[] Returns an array of Event objects
     */
    public function searchEventsInvitedByTitle($title, $userId)
    {   
        $queryBuilder = $this->_em->createQueryBuilder()
           ->select('e')
           ->from(Event::class, 'e')
           ->innerJoin(Participation::class, 'p', Join::WITH, 'e.id = p.event')
           ->where('e.title LIKE :title')
           ->andWhere('p.user = :user')
           ->setParameter(':title', "%$title%")
           ->setParameter(':user', $userId);

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
    
    // select e.*
    // from event e
    // join participation p
    // on p.event_id = e.id
    // and p.user = :user
    // where c.title = :title

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
