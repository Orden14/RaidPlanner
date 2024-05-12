<?php

namespace App\Repository;

use App\Entity\GuildEventRelation\PlayerSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlayerSlot>
 *
 * @method PlayerSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerSlot[]    findAll()
 * @method PlayerSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class PlayerSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerSlot::class);
    }
}
