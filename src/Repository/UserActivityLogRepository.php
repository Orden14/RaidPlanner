<?php

namespace App\Repository;

use App\Entity\UserActivityLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserActivityLog>
 *
 * @method UserActivityLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserActivityLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserActivityLog[]    findAll()
 * @method UserActivityLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class UserActivityLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserActivityLog::class);
    }
}
