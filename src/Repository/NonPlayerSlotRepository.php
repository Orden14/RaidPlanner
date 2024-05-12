<?php

namespace App\Repository;

use App\Entity\GuildEventRelation\NonPlayerSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NonPlayerSlot>
 *
 * @method NonPlayerSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method NonPlayerSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method NonPlayerSlot[]    findAll()
 * @method NonPlayerSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class NonPlayerSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NonPlayerSlot::class);
    }
}
