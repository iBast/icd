<?php

namespace App\Repository;

use App\Entity\ShopProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShopProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopProduct[]    findAll()
 * @method ShopProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopProductRepository extends ServiceEntityRepository
{
    const ALIAS = "sp";

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopProduct::class);
    }

    public function findVisible()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isVisible = true')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return ShopProduct[] Returns an array of ShopProduct objects
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
    public function findOneBySomeField($value): ?ShopProduct
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
