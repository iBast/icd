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

use App\Entity\Accounting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Accounting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Accounting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Accounting[]    findAll()
 * @method Accounting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountingRepository extends ServiceEntityRepository
{
    public const ALIAS = 'a';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Accounting::class);
    }

    public function findByDates($begining, $end)
    {
        $qb = $this->createQueryBuilder(self::ALIAS)
            ->where(self::ALIAS.'.date >= :begining')
            ->andWhere(self::ALIAS.'.date <= :end')
            ->setParameter('begining', $begining)
            ->setParameter('end', $end)
            ->getQuery()
            ->execute();

        return $qb;
    }

    // /**
    //  * @return Accounting[] Returns an array of Accounting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Accounting
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
