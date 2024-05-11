<?php

namespace App\Repository;

use App\Entity\GuildEventRelation\EventSlot;
use App\Enum\EventSlotTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventSlot>
 *
 * @method EventSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventSlot[]    findAll()
 * @method EventSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class EventSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventSlot::class);
    }

    /**
     * @return EventSlot[]
     */
    public function findByEventIdAndType(int $eventId, EventSlotTypeEnum $type): array
    {
        return $this->findBy([
            'guildEvent' => $eventId,
            'slotType' => $type
        ]);
    }
}
