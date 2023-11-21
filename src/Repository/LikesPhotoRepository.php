<?php

namespace App\Repository;

use App\Entity\LikesPhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LikesPhoto>
 *
 * @method LikesPhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikesPhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikesPhoto[]    findAll()
 * @method LikesPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikesPhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LikesPhoto::class);
    }

//    /**
//     * @return LikesPhoto[] Returns an array of LikesPhoto objects
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

//    public function findOneBySomeField($value): ?LikesPhoto
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
