<?php

namespace App\Repository;

use App\Entity\GuildEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GuildEvent>
 *
 * @method GuildEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method GuildEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method GuildEvent[]    findAll()
 * @method GuildEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class GuildEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuildEvent::class);
    }
}
