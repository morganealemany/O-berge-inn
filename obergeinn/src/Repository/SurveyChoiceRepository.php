<?php

namespace App\Repository;

use App\Entity\SurveyChoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SurveyChoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyChoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyChoice[]    findAll()
 * @method SurveyChoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyChoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyChoice::class);
    }

    // /**
    //  * @return SurveyChoice[] Returns an array of SurveyChoice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SurveyChoice
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
