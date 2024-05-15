<?php

namespace App\Checker\EventSignupPermission;

use App\Entity\GuildEvent;
use App\Interface\EventAttendancePermissionStepInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final readonly class EventSignupPermissionChecker
{
    public function __construct(
        /** @var EventAttendancePermissionStepInterface[] $eventSignupPermissionStepInterfaces */
        #[TaggedIterator(EventAttendancePermissionStepInterface::class)]
        private iterable $eventSignupPermissionStepInterfaces
    ) {}

    public function checkIfUserCanSignup(GuildEvent $guildEvent): bool
    {
        foreach ($this->eventSignupPermissionStepInterfaces as $eventSignupPermissionStepInterface) {
            if (!$eventSignupPermissionStepInterface->check($guildEvent)) {
                return false;
            }
        }

        return true;
    }
}
