<?php

namespace App\Checker\EventSignupPermission\Steps;

use App\Entity\GuildEvent;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Interface\EventAttendancePermissionStepInterface;
use App\Service\GuildEvent\EventAttendanceDataService;
use Override;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class UserIsNotAlreadySignedUpStep implements EventAttendancePermissionStepInterface
{
    public function __construct(
        private Security                   $security,
        private EventAttendanceDataService $eventAttendanceDataService,
    ) {}

    #[Override]
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
