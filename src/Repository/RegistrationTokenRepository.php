<?php

namespace App\Repository;

use App\Entity\RegistrationToken;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RegistrationToken>
 *
 * @method RegistrationToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegistrationToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegistrationToken[] findAll()
 * @method RegistrationToken[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class RegistrationTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegistrationToken::class);
    }

    /**
     * @return RegistrationToken[]
     */
    public function findExpiredTokens(): array
    {
        $now = new DateTime();

        return $this->createQueryBuilder('t')
            ->where('t.expiryDate < :dateTime')
            ->orWhere('t.used >= t.uses AND t.uses >= 0')
            ->setParameter('dateTime', $now)
            ->getQuery()
            ->getResult()
        ;
    }
}
