<?php

namespace App\Repository;

use App\Entity\BuildCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BuildCategory>
 *
 * @method BuildCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuildCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuildCategory[]    findAll()
 * @method BuildCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuildCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuildCategory::class);
    }

    //    /**
    //     * @return BuildCategory[] Returns an array of BuildCategory objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BuildCategory
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
