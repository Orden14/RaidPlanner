<?php

namespace App\Util\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\User;
use App\Repository\NonPlayerSlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class NonPlayerSlotManager
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private NonPlayerSlotRepository $nonPlayerSlotRepository,
    ) {}

    public function manageNonPlayerSlotsForUser(GuildEvent $guildEvent): void
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $nonPlayerSlots = $this->nonPlayerSlotRepository->findBy(['guildEvent' => $guildEvent]);

        foreach ($nonPlayerSlots as $slot) {
            if ($slot->getUser() === $currentUser) {
                $this->entityManager->remove($slot);
            }
        }

        $this->entityManager->flush();
    }
}
