<?php

namespace App\Repository;

use App\Entity\GuildEventRelation\EventEncounter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventEncounter>
 *
 * @method EventEncounter|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventEncounter|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventEncounter[]    findAll()
 * @method EventEncounter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class EventEncounterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventEncounter::class);
    }
}
