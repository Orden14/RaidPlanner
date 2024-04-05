<?php

namespace App\Repository;

use App\Entity\Specialization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Specialization>
 *
 * @method Specialization|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specialization|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specialization[]    findAll()
 * @method Specialization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class SpecializationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specialization::class);
    }

    /**
     * @return Specialization[]
     */
    public function findAllOrderedByJob(): array
    {
        return $this->createQueryBuilder('s')
            ->join('s.job', 'j')
            ->andWhere('j.isDefault = false') // This is the new line that filters out the default job
            ->orderBy('s.job', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
