<?php

namespace App\Repository;

use App\Entity\GuildEvent;
use App\Enum\GuildEventStatusEnum;
use App\Util\DateHelper;
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

    /**
     * @return GuildEvent[]
     */
    public function findWeeklyGuildRaids(): array
    {
        return $this->createQueryBuilder('ge')
            ->where('ge.guildRaid = true')
            ->andWhere('ge.status = :status')
            ->andWhere('ge.start >= :startOfWeek')
            ->andWhere('ge.start < :endOfWeek')
            ->setParameter('status', GuildEventStatusEnum::OPEN->value)
            ->setParameter('startOfWeek', DateHelper::getStartOfWeek())
            ->setParameter('endOfWeek', DateHelper::getEndOfWeek())
            ->orderBy('ge.start', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
