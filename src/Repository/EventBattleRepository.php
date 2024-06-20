<?php

namespace App\Repository;

use App\Entity\GuildEventRelation\EventBattle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventBattle>
 *
 * @method EventBattle|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventBattle|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventBattle[] findAll()
 * @method EventBattle[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class EventBattleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventBattle::class);
    }
}
