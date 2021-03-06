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

use App\Entity\ShopProductVariant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShopProductVariant|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopProductVariant|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopProductVariant[]    findAll()
 * @method ShopProductVariant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopProductVariantRepository extends ServiceEntityRepository
{
    public const ALIAS = 'spv';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopProductVariant::class);
    }

    public function findAllWithStock()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->andWhere('p.stock > 0')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return ShopProductVariant[] Returns an array of ShopProductVariant objects
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
    public function findOneBySomeField($value): ?ShopProductVariant
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
