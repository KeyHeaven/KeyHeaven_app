<?php

namespace App\Repository;

use App\Entity\PurchaseDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PurchaseDetail>
 *
 * @method PurchaseDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurchaseDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurchaseDetail[]    findAll()
 * @method PurchaseDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurchaseDetail::class);
    }

    public function findByPurchaseId($purchaseId)
    {
        $qb = $this->createQueryBuilder('pd')
            ->innerJoin('pd.purchase', 'p')
            ->where('p.id = :purchaseId')
            ->setParameter('purchaseId', $purchaseId);

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return PurchaseDetail[] Returns an array of PurchaseDetail objects
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

//    public function findOneBySomeField($value): ?PurchaseDetail
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
