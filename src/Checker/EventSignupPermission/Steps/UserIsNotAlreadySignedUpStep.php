<?php

namespace App\Checker\EventSignupPermission\Steps;

use App\Entity\GuildEvent;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Interface\EventAttendancePermissionStepInterface;
use App\Service\GuildEvent\EventAttendanceDataService;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class UserIsNotAlreadySignedUpStep implements EventAttendancePermissionStepInterface
{
    public function __construct(
        private Security                   $security,
        private EventAttendanceDataService $eventAttendanceDataService,
    ) {}

    public function check(GuildEvent $guildEvent): bool
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $playerAttendances = $this->eventAttendanceDataService->getAttendanceListByType($guildEvent, AttendanceTypeEnum::PLAYER);

        foreach ($playerAttendances as $attendance) {
            if ($attendance->getUser() === $currentUser) {
                return false;
            }
        }

        return true;
    }
}
