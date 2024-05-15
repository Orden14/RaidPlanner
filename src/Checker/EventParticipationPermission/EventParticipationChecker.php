<?php

namespace App\Checker\EventParticipationPermission;

use App\Entity\GuildEvent;
use App\Interface\EventParticipationPermissionStepInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final readonly class EventParticipationChecker
{
    public function __construct(
        /** @var EventParticipationPermissionStepInterface[] $eventParticipationPermissionStepInterfaces */
        #[TaggedIterator(EventParticipationPermissionStepInterface::class)]
        private iterable $eventParticipationPermissionStepInterfaces
    ) {}

    public function checkIfUserIsAllowedInEvent(GuildEvent $guildEvent): bool
    {
        foreach ($this->eventParticipationPermissionStepInterfaces as $eventParticipationPermissionStepInterface) {
            if (!$eventParticipationPermissionStepInterface->check($guildEvent)) {
                return false;
            }
        }

        return true;
    }
}
