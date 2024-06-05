<?php

namespace App\Checker\SlotAssignmentPermission\Steps;

use App\Entity\GuildEventRelation\EventBattle;
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
    public function check(EventBattle $eventBattle): bool
    {
        $playerSlots = $this->playerSlotRepository->findBy(['eventBattle' => $eventBattle]);

        foreach ($playerSlots as $slot) {
            if ($slot->getPlayer() === $this->security->getUser()) {
                return false;
            }
        }

        return true;
    }
}
