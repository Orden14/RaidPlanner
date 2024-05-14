<?php

namespace App\Repository;

use App\Entity\GuildEventRelation\NonActiveSlot;
use App\Enum\NonActiveSlotTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NonActiveSlot>
 *
 * @method NonActiveSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method NonActiveSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method NonActiveSlot[]    findAll()
 * @method NonActiveSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class NonActiveSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NonActiveSlot::class);
    }

    /**
     * @return NonActiveSlot[]
     */
    public function findBackupsByEvent(int $eventId): array
    {
        return $this->createQueryBuilder('nps')
            ->where('nps.guildEvent = :eventId')
            ->andWhere('nps.type = :type')
            ->setParameter('eventId', $eventId)
            ->setParameter('type', NonActiveSlotTypeEnum::BACKUP->value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return NonActiveSlot[]
     */
    public function findAbsentsByEvent(int $eventId): array
    {
        return $this->createQueryBuilder('nps')
            ->where('nps.guildEvent = :eventId')
            ->andWhere('nps.type = :type')
            ->setParameter('eventId', $eventId)
            ->setParameter('type', NonActiveSlotTypeEnum::ABSENT->value)
            ->getQuery()
            ->getResult()
            ;
    }
}
