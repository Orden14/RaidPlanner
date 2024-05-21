<?php

namespace App\Repository;

use App\Entity\Build;
use App\Enum\BuildStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Build>
 *
 * @method Build|null find($id, $lockMode = null, $lockVersion = null)
 * @method Build|null findOneBy(array $criteria, array $orderBy = null)
 * @method Build[]    findAll()
 * @method Build[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class BuildRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Build::class);
    }

    /**
     * @return Build[]
     */
    public function findMetaBuilds(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.status = :status')
            ->setParameter('status', BuildStatusEnum::META->value)
            ->orderBy('b.specialization', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Build[]
     */
    public function findByDefault(bool $isDefault = false): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.specialization', 's')
            ->join('s.job', 'j')
            ->where('j.defaultJob = :isDefault')
            ->setParameter('isDefault', $isDefault)
            ->getQuery()
            ->getResult();
    }
}
