<?php

namespace App\Repository;

use App\Entity\GuildEventRelation\GuildEventEncounter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GuildEventEncounter>
 *
 * @method GuildEventEncounter|null find($id, $lockMode = null, $lockVersion = null)
 * @method GuildEventEncounter|null findOneBy(array $criteria, array $orderBy = null)
 * @method GuildEventEncounter[]    findAll()
 * @method GuildEventEncounter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class GuildEventEncounterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuildEventEncounter::class);
    }
}
