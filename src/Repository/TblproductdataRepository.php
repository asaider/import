<?php

namespace App\Repository;

use App\Entity\Tblproductdata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tblproductdata|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tblproductdata|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tblproductdata[]    findAll()
 * @method Tblproductdata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TblproductdataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tblproductdata::class);
    }


    public function findOneByProductCode($value): ?Tblproductdata
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.strproductcode = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
    // /**
    //  * @return Tblproductdata[] Returns an array of Tblproductdata objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tblproductdata
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


}
