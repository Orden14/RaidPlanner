<?php

namespace App\Repository;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\Specialization;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlayerSlot>
 *
 * @method PlayerSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerSlot[] findAll()
 * @method PlayerSlot[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class PlayerSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerSlot::class);
    }

    /**
     * @return PlayerSlot[]
     */
    public function findSlotsByEventsForUser(GuildEvent $guildEvent, User $user): array
    {
        return $this->createQueryBuilder('ps')
            ->join('ps.eventBattle', 'ee')
            ->where('ee.guildEvent = :guildEvent')
            ->andWhere('ps.player = :user')
            ->setParameter('guildEvent', $guildEvent)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return PlayerSlot[]
     */
    public function findSlotsBySpecialization(Specialization $specialization): array
    {
        return $this->createQueryBuilder('ps')
            ->join('ps.build', 'b')
            ->join('b.specialization', 's')
            ->where('s = :specialization')
            ->setParameter('specialization', $specialization)
            ->getQuery()
            ->getResult()
        ;
    }
}
