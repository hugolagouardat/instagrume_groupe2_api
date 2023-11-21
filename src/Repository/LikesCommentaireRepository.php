<?php

namespace App\Repository;

use App\Entity\LikesCommentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LikesCommentaire>
 *
 * @method LikesCommentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikesCommentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikesCommentaire[]    findAll()
 * @method LikesCommentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikesCommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LikesCommentaire::class);
    }

//    /**
//     * @return LikesCommentaire[] Returns an array of LikesCommentaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LikesCommentaire
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
