<?php

namespace App\Repository;

use App\Entity\Encounter;
use App\Enum\InstanceTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Encounter>
 *
 * @method Encounter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Encounter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Encounter[] findAll()
 * @method Encounter[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class EncounterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Encounter::class);
    }

    /**
     * @return Encounter[]
     */
    public function findAllByType(InstanceTypeEnum $type = InstanceTypeEnum::RAID): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.instance', 'i')
            ->where('i.type = :type')
            ->setParameter('type', $type->value)
            ->orderBy('i.tag', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
