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

use App\Entity\EventComment;
use App\Entity\Race;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventComment[]    findAll()
 * @method EventComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventComment::class);
    }

    public function findComments(Race $race)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.event = :race')
            ->setParameter('race', $race)
            ->andWhere('c.isPinned = FALSE')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPinnedComments(Race $race)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.event = :race')
            ->setParameter('race', $race)
            ->andWhere('c.isPinned = TRUE')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return EventComment[] Returns an array of EventComment objects
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
    public function findOneBySomeField($value): ?EventComment
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
