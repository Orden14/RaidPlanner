<?php

namespace App\Util\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\User;
use App\Repository\NonActiveSlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class NonActiveSlotManager
{
    public function __construct(
        private Security                $security,
        private EntityManagerInterface  $entityManager,
        private NonActiveSlotRepository $nonActiveSlotRepository,
    ) {}

    public function manageNonActiveSlotsForUser(GuildEvent $guildEvent): void
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $nonActiveSlots = $this->nonActiveSlotRepository->findBy(['guildEvent' => $guildEvent]);

        foreach ($nonActiveSlots as $slot) {
            if ($slot->getUser() === $currentUser) {
                $this->entityManager->remove($slot);
            }
        }

        $this->entityManager->flush();
    }
}
