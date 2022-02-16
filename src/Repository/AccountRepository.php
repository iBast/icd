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

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function getProducts()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.number LIKE :val')
            ->setParameter('val', '7%')
            ->orderBy('a.number', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getCharges()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.number LIKE :val')
            ->setParameter('val', '6%')
            ->orderBy('a.number', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getAccounts(array $numbers)
    {
        $querry = $this->createQueryBuilder('a');

        foreach ($numbers as $number) {
            $querry
                ->andWhere('a.number LIKE :val')
                ->setParameter('val', $number);
        }

        return $querry->orderBy('a.number', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Account[] Returns an array of Account objects
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
    public function findOneBySomeField($value): ?Account
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
