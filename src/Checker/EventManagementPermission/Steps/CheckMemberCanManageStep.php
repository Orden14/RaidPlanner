<?php

namespace App\Checker\EventManagementPermission\Steps;

use App\Entity\GuildEvent;
use App\Entity\User;
use App\Enum\RolesEnum;
use App\Interface\EventManagementPermissionStepInterface;
use App\Repository\EventAttendanceRepository;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CheckMemberCanManageStep implements EventManagementPermissionStepInterface
{
    public function __construct(
        private Security                  $security,
        private EventAttendanceRepository $eventAttendanceRepository,
    ) {}

    public function check(GuildEvent $guildEvent): bool
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $eventAttendance = $this->eventAttendanceRepository->findOneBy([
            'guildEvent' => $guildEvent,
            'user'     => $currentUser,
        ]);

        return $this->security->isGranted(RolesEnum::ADMIN->value)
            || ($eventAttendance && $eventAttendance->isEventOwner())
            || ($guildEvent->canMembersManageEvent() && $this->security->isGranted(RolesEnum::MEMBER->value));
    }
}
