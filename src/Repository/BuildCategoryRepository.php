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
 * @method BuildCategory[] findAll()
 * @method BuildCategory[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class BuildCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuildCategory::class);
    }
}
