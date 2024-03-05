<?php

namespace App\Repository;

use App\Entity\Purchasing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Purchasing>
 *
 * @method Purchasing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Purchasing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Purchasing[]    findAll()
 * @method Purchasing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchasingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purchasing::class);
    }

//    /**
//     * @return Purchasing[] Returns an array of Purchasing objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Purchasing
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
