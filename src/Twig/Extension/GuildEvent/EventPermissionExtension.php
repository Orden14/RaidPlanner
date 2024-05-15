<?php

namespace App\Twig\Extension\GuildEvent;

use App\Checker\EventManagementPermission\EventManagementPermissionChecker;
use App\Checker\EventSignupPermission\EventSignupPermissionChecker;
use App\Checker\SlotAssignmentPermission\SlotAssignmentPermissionChecker;
use App\Entity\GuildEvent;
use App\Repository\EventBattleRepository;
use App\Repository\GuildEventRepository;
use Doctrine\ORM\EntityNotFoundException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EventPermissionExtension extends AbstractExtension
{
    public function __construct(
        private readonly GuildEventRepository             $guildEventRepository,
        private readonly EventBattleRepository            $eventBattleRepository,
        private readonly EventSignupPermissionChecker     $eventSignupPermissionChecker,
        private readonly SlotAssignmentPermissionChecker  $slotAssignmentPermissionChecker,
        private readonly EventManagementPermissionChecker $eventManagementPermissionChecker,
    ) {}

    /**
     * @return TwigFunction[]
     */
    final public function getFunctions(): array
    {
        return [
            new TwigFunction('can_user_manage_event', $this->canUserManageEvent(...)),
            new TwigFunction('can_user_take_slot', $this->canUserTakeSlot(...)),
            new TwigFunction('can_user_signup', $this->canUserSignup(...)),
        ];
    }

    final public function canUserManageEvent(int $guildEventId): bool
    {
        $guildEvent = $this->getGuildEvent($guildEventId);

        return $this->eventManagementPermissionChecker->checkIfUserCanManageEvent($guildEvent);
    }

    /**
     * @throws EntityNotFoundException
     */
    final public function canUserTakeSlot(int $eventBattleId): bool
    {
        $eventBattle = $this->eventBattleRepository->find($eventBattleId);

        if (!$eventBattle) {
            throw new EntityNotFoundException(sprintf('Event battle with id %d not found', $eventBattleId));
        }

        return $this->slotAssignmentPermissionChecker->checkIfUserCanTakeSlot($eventBattle);
    }

    final public function canUserSignup(int $guildEventId): bool
    {
        $guildEvent = $this->getGuildEvent($guildEventId);

        return $this->eventSignupPermissionChecker->checkIfUserCanSignup($guildEvent);
    }

    private function getGuildEvent(int $guildEventId): GuildEvent
    {
        $guildEvent = $this->guildEventRepository->find($guildEventId);

        if (!$guildEvent) {
            throw new EntityNotFoundException(sprintf('Guild event with id %d not found', $guildEventId));
        }

        return $guildEvent;
    }
}
