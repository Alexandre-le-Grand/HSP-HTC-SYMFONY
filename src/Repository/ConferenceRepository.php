<?php

namespace App\Repository;

use App\Entity\Amphitheatre;
use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conference>
 *
 * @method Conference|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conference|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conference[]    findAll()
 * @method Conference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    public function countValidConferences(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->andWhere('c.statut = :statut')
            ->setParameter('statut', 1)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function isAmphitheatreAvailableForTimeRange(Amphitheatre $amphitheatre, \DateTime $start, \DateTime $end, int $conferenceId = null)
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.ref_amphi = :amphitheatre')
            ->andWhere(':end > c.date') // Vérifier si la date de début est avant la date de fin de la conférence
            ->andWhere(':start < DATE_ADD(c.date, c.duree, \'MINUTE\')') // Vérifier si la date de fin est après la date de début de la conférence
            ->setParameter('amphitheatre', $amphitheatre)
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        if ($conferenceId) {
            $qb->andWhere('c.id != :conferenceId')
                ->setParameter('conferenceId', $conferenceId);
        }

        $result = $qb->getQuery()->getResult();

        return empty($result);
    }


//    /**
//     * @return Conference[] Returns an array of Conference objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Conference
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
