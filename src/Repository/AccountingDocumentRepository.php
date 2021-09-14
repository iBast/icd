<?php

namespace App\Repository;

use App\Entity\AccountingDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccountingDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountingDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountingDocument[]    findAll()
 * @method AccountingDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountingDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountingDocument::class);
    }

    // /**
    //  * @return AccountingDocument[] Returns an array of AccountingDocument objects
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
    public function findOneBySomeField($value): ?AccountingDocument
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
