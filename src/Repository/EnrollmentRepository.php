<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Season;
use App\Entity\Enrollment;
use App\Entity\Member;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Enrollment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enrollment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enrollment[]    findAll()
 * @method Enrollment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnrollmentRepository extends ServiceEntityRepository
{
    public const ALIAS = 'e';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enrollment::class);
    }


    public function findBySeasonAndUser(Season $season, User $user)
    {
        $qb = $this->createQueryBuilder(self::ALIAS)
            ->where(self::ALIAS . '.Season
            = :season')
            ->setParameter('season', $season)
            ->andWhere(self::ALIAS . '.User = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
        return $qb;
    }



    // /**
    //  * @return Enrollment[] Returns an array of Enrollment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Enrollment
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
