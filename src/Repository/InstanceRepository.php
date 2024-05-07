<?php

namespace App\Repository;

use App\Entity\Instance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Instance>
 *
 * @method Instance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Instance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Instance[]    findAll()
 * @method Instance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class InstanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instance::class);
    }
}
