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

use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PurchaseItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurchaseItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurchaseItem[]    findAll()
 * @method PurchaseItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseItemRepository extends ServiceEntityRepository
{
    public const ALIAS = 'pi';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurchaseItem::class);
    }

    public function findPurchasedItemOrdered()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS.'.ProductName')
            ->addSelect(self::ALIAS.'.variantName')
            ->addSelect(ShopProductRepository::ALIAS.'.reference')
            ->addSelect('SUM('.self::ALIAS.'.quantity) as count')
            ->Join(self::ALIAS.'.purchase', PurchaseRepository::ALIAS)
            ->Join(self::ALIAS.'.productVariant', ShopProductVariantRepository::ALIAS)
            ->Join(ShopProductVariantRepository::ALIAS.'.product', ShopProductRepository::ALIAS)
            ->where(PurchaseRepository::ALIAS.'.status = :status')
            ->setParameter('status', Purchase::STATUS_ACCEPTED)
            ->groupBy(self::ALIAS.'.ProductName, '.self::ALIAS.'.variantName,'.ShopProductRepository::ALIAS.'.reference')
            ->orderBy(self::ALIAS.'.ProductName')
            ->getQuery()
            ->getScalarResult();
    }

    // /**
    //  * @return PurchaseItem[] Returns an array of PurchaseItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PurchaseItem
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
