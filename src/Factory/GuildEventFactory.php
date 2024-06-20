<?php

namespace App\Factory;

use App\Entity\GuildEvent;
use App\Entity\User;
use App\Enum\RolesEnum;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class GuildEventFactory
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function generateGuildEvent(): GuildEvent
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $guildEvent = new GuildEvent();

        if ($currentUser->getRole() === RolesEnum::OLD_MEMBER) {
            $guildEvent->setOldMembersAllowed(true);
        }

        if (in_array($currentUser->getRole(), [RolesEnum::OLD_MEMBER, RolesEnum::TRIAL, RolesEnum::MEMBER], true)) {
            $guildEvent->setMembersManageEvent(true);
        }

        return $guildEvent;
    }
}
