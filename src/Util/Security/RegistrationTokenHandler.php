<?php

namespace App\Util\Security;

use App\Repository\RegistrationTokenRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

final readonly class RegistrationTokenHandler
{
    public function __construct(
        private EntityManagerInterface      $entityManager,
        private RegistrationTokenRepository $registrationTokenRepository,
    ) {}

    public function checkToken(string $token): bool
    {
        $registrationToken = $this->registrationTokenRepository->findOneBy(['token' => $token]);

         return $registrationToken !== null
            && ($registrationToken->getExpiryDate() > new DateTime())
            && ($registrationToken->getUsed() - $registrationToken->getUses() < 0);
    }

    public function incrementTokenUsage(string $token): void
    {
        $registrationToken = $this->registrationTokenRepository->findOneBy(['token' => $token]);

        if ($registrationToken) {
            $registrationToken->setUsed($registrationToken->getUsed() + 1);
            $this->entityManager->flush();
        }

        $this->clearExpiredTokens();
    }

    public function clearExpiredTokens(): void
    {
        foreach ($this->registrationTokenRepository->findExpiredTokens() as $expiredToken) {
            $this->entityManager->remove($expiredToken);
        }

        $this->entityManager->flush();
    }

}
