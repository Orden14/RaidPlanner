<?php

namespace App\Util\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventEncounter;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Enum\RolesEnum;
use App\Repository\PlayerSlotRepository;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class PlayerSlotChecker
{
    public function __construct(
        private Security $security,
        private PlayerSlotRepository $playerSlotRepository,
    ) {}

    public function isUserAllowedToSignUp(EventEncounter $eventEncounter): bool
    {
        return $this->doesUserHavePermission($eventEncounter->getGuildEvent()) && !$this->doesUserAlreadyHaveSlot($eventEncounter);
    }

    public function isUserAllowedToFreeSlot(PlayerSlot $playerSlot): bool
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        return $this->security->isGranted(RolesEnum::ADMIN->value) || $currentUser === $playerSlot->getPlayer();
    }

    public function doesUserAlreadyHaveSlot(EventEncounter $eventEncounter): bool
    {
        $playerSlots = $this->playerSlotRepository->findBy(['eventEncounter' => $eventEncounter]);

        foreach ($playerSlots as $slot) {
            if ($slot->getPlayer() === $this->security->getUser()) {
                return true;
            }
        }

        return false;
    }

    private function doesUserHavePermission(GuildEvent $guildEvent): bool
    {
        return ($guildEvent->isOldMembersAllowed() && $this->security->isGranted(RolesEnum::OLD_MEMBER->value))
            || (!$guildEvent->isOldMembersAllowed() && $this->security->isGranted(RolesEnum::TRIAL->value));
    }
}
