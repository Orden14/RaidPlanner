<?php

namespace App\Checker\SlotAssignmentPermission\Steps;

use App\Entity\GuildEventRelation\EventEncounter;
use App\Interface\SlotAssignmentPermissionStepInterface;
use App\Repository\PlayerSlotRepository;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CheckUserHasNoSlotStep implements SlotAssignmentPermissionStepInterface
{
    public function __construct(
        private Security             $security,
        private PlayerSlotRepository $playerSlotRepository,
    ) {}

    public function check(EventEncounter $eventEncounter): bool
    {
        $playerSlots = $this->playerSlotRepository->findBy(['eventEncounter' => $eventEncounter]);

        foreach ($playerSlots as $slot) {
            if ($slot->getPlayer() === $this->security->getUser()) {
                return false;
            }
        }

        return true;
    }
}
