<?php

namespace App\Util\Log;

use App\Entity\Log;
use App\Entity\User;
use App\Enum\LogTypeEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class LogManager
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager
    ) {}

    public function log(LogTypeEnum $type, ?int $targetId = null, ?string $targetText = null): void
    {
        /** @var ?User $user */
        $user = $this->security->getUser();

        $log = (new Log())
            ->setUser($user)
            ->setType($type)
            ->setTargetId($targetId)
            ->setTargetText($targetText);

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
