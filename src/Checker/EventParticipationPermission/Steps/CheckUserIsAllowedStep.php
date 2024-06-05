<?php

namespace App\Checker\EventParticipationPermission\Steps;

use App\Entity\GuildEvent;
use App\Entity\User;
use App\Enum\RolesEnum;
use App\Interface\EventParticipationPermissionStepInterface;
use App\Repository\EventAttendanceRepository;
use Override;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CheckUserIsAllowedStep implements EventParticipationPermissionStepInterface
{
    public function __construct(
        private Security                  $security,
        private EventAttendanceRepository $eventAttendanceRepository
    ) {}

    #[Override]
    public function check(GuildEvent $guildEvent): bool
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $isCurrentUserOwner = count($this->eventAttendanceRepository->findBy([
                'user' => $currentUser,
                'guildEvent' => $guildEvent,
                'eventOwner' => true
            ])) > 0;

        return $this->security->isGranted(RolesEnum::TRIAL->value)
            || $isCurrentUserOwner
            || ($guildEvent->isOldMembersAllowed() && $this->security->isGranted(RolesEnum::OLD_MEMBER->value));
    }
}
