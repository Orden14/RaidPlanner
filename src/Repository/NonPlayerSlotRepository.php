<?php

namespace App\Repository;

use App\Entity\GuildEventRelation\NonPlayerSlot;
use App\Enum\NonPlayerSlotTypeEnum;
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

    /**
     * @return NonPlayerSlot[]
     */
    public function findBackupsByEvent(int $eventId): array
    {
        return $this->createQueryBuilder('nps')
            ->where('nps.guildEvent = :eventId')
            ->andWhere('nps.type = :type')
            ->setParameter('eventId', $eventId)
            ->setParameter('type', NonPlayerSlotTypeEnum::BACKUP->value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return NonPlayerSlot[]
     */
    public function findAbsentsByEvent(int $eventId): array
    {
        return $this->createQueryBuilder('nps')
            ->where('nps.guildEvent = :eventId')
            ->andWhere('nps.type = :type')
            ->setParameter('eventId', $eventId)
            ->setParameter('type', NonPlayerSlotTypeEnum::ABSENT->value)
            ->getQuery()
            ->getResult()
            ;
    }
}
