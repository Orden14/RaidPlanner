<?php

namespace App\Checker\SlotAssignmentPermission\Steps;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Enum\InstanceTypeEnum;
use App\Interface\SlotAssignmentPermissionStepInterface;
use App\Service\GuildEvent\EventAttendanceDataService;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CheckEventIsNotFullStep implements SlotAssignmentPermissionStepInterface
{
    public function __construct(
        private Security                   $security,
        private EventAttendanceDataService $eventAttendanceDataService,
    ) {}

    public function check(PlayerSlot $playerSlot): bool
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        /** @var EventBattle $eventBattle */
        $eventBattle = $playerSlot->getEventBattle();

        /** @var GuildEvent $guildEvent */
        $guildEvent = $eventBattle->getGuildEvent();

        return $this->eventAttendanceDataService->isUserAttendingAsPlayer($guildEvent, $currentUser)
            || count($this->eventAttendanceDataService->getAttendanceListByType($guildEvent, AttendanceTypeEnum::PLAYER)) < InstanceTypeEnum::getMaxPlayersByType($guildEvent->getType());
    }
}
