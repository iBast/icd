<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\EnrollmentYoung;
use App\Entity\Season;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EnrollmentYoung|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnrollmentYoung|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnrollmentYoung[]    findAll()
 * @method EnrollmentYoung[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnrollmentYoungRepository extends ServiceEntityRepository
{
    public const ALIAS = 'y';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnrollmentYoung::class);
    }

    public function findBySeasonAndUser(Season $season, User $user)
    {
        $qb = $this->createQueryBuilder(self::ALIAS)
            ->where(self::ALIAS.'.season
            = :season')
            ->setParameter('season', $season)
            ->andWhere(self::ALIAS.'.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();

        return $qb;
    }

    // /**
    //  * @return EnrollmentYoung[] Returns an array of EnrollmentYoung objects
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
    public function findOneBySomeField($value): ?EnrollmentYoung
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
