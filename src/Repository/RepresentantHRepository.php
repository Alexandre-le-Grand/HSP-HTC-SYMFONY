<?php

namespace App\Repository;

use App\Entity\RepresentantH;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RepresentantH>
 *
 * @method RepresentantH|null find($id, $lockMode = null, $lockVersion = null)
 * @method RepresentantH|null findOneBy(array $criteria, array $orderBy = null)
 * @method RepresentantH[]    findAll()
 * @method RepresentantH[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepresentantHRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RepresentantH::class);
    }

//    /**
//     * @return RepresentantH[] Returns an array of RepresentantH objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RepresentantH
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
