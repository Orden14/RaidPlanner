<?php

namespace App\Checker\EventManagementPermission;

use App\Entity\GuildEvent;
use App\Interface\EventManagementPermissionStepInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final readonly class EventManagementPermissionChecker
{
    public function __construct(
        /** @var EventManagementPermissionStepInterface[] $eventManagementPermissionStepInterfaces */
        #[TaggedIterator(EventManagementPermissionStepInterface::class)]
        private iterable $eventManagementPermissionStepInterfaces
    ) {
    }

    public function checkIfUserCanManageEvent(GuildEvent $guildEvent): bool
    {
        foreach ($this->eventManagementPermissionStepInterfaces as $eventManagementPermissionStepInterface) {
            if (!$eventManagementPermissionStepInterface->check($guildEvent)) {
                return false;
            }
        }

        return true;
    }
}
