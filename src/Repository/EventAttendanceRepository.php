<?php

namespace App\Repository;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventAttendance>
 *
 * @method EventAttendance|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventAttendance|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventAttendance[]    findAll()
 * @method EventAttendance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class EventAttendanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventAttendance::class);
    }

    /**
     * @return EventAttendance[]
     */
    public function findEventAttendancesByType(GuildEvent $eventId, AttendanceTypeEnum $type): array
    {
        return $this->createQueryBuilder('nps')
            ->where('nps.guildEvent = :eventId')
            ->andWhere('nps.type = :type')
            ->setParameter('eventId', $eventId)
            ->setParameter('type', $type->value)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param AttendanceTypeEnum[] $types
     *
     * @return EventAttendance[]
     */
    public function findEventAttendancesByTypesForPlayer(GuildEvent $guildEvent, User $user, array $types): array
    {
        return $this->createQueryBuilder('ea')
            ->where('ea.user = :user')
            ->andWhere('ea.type IN (:types)')
            ->andWhere('ea.guildEvent = :guildEvent')
            ->setParameter('types', $types)
            ->setParameter('guildEvent', $guildEvent)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
