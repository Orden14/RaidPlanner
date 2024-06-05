<?php

namespace App\Checker\SlotAssignmentPermission\Steps;

use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Interface\SlotAssignmentPermissionStepInterface;
use App\Repository\PlayerSlotRepository;
use Override;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CheckUserHasNoSlotStep implements SlotAssignmentPermissionStepInterface
{
    public function __construct(
        private Security             $security,
        private PlayerSlotRepository $playerSlotRepository,
    ) {}

    #[Override]
    public function check(PlayerSlot $playerSlot): bool
    {
        $playerSlots = $this->playerSlotRepository->findBy(['eventBattle' => $playerSlot->getEventBattle()]);

        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        foreach ($playerSlots as $slot) {
            if ($slot->getPlayer() === $currentUser) {
                return false;
            }
        }

        return true;
    }
}
