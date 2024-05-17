<?php

namespace App\Factory;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class EventAttendanceFactory
{
    public function __construct(
        private Security $security
    ) {}

    public function generateEventAttendance(GuildEvent $guildEvent, bool $owner = false): EventAttendance
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        return (new EventAttendance())
            ->setType(AttendanceTypeEnum::PLAYER)
            ->setUser($currentUser)
            ->setGuildEvent($guildEvent)
            ->setEventOwner($owner);
    }
}
