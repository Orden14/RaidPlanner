<?php

namespace App\Repository;

use App\Entity\CombatLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CombatLog>
 *
 * @method CombatLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method CombatLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method CombatLog[]    findAll()
 * @method CombatLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CombatLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CombatLog::class);
    }
}
