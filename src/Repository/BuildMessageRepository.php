<?php

namespace App\Repository;

use App\Entity\BuildMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BuildMessage>
 *
 * @method BuildMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuildMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuildMessage[] findAll()
 * @method BuildMessage[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class BuildMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuildMessage::class);
    }
}
