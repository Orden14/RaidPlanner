<?php

namespace App\Repository;

use App\Entity\GuildEventRelation\GuildEventSlot;
use App\Enum\GuildEventSlotTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GuildEventSlot>
 *
 * @method GuildEventSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method GuildEventSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method GuildEventSlot[]    findAll()
 * @method GuildEventSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class GuildEventSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuildEventSlot::class);
    }

    /**
     * @return GuildEventSlot[]
     */
    public function findByIdAndType(int $id, GuildEventSlotTypeEnum $type): array
    {
        return $this->findBy([
            'id' => $id,
            'slotType' => $type
        ]);
    }
}
