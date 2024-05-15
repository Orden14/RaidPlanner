<?php

namespace App\Twig\Extension\GuildEvent;

use App\Checker\SlotAssignmentPermission\SlotAssignmentChecker;
use App\Repository\EventEncounterRepository;
use Doctrine\ORM\EntityNotFoundException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PlayerSlotExtension extends AbstractExtension
{
    public function __construct(
        private readonly SlotAssignmentChecker    $slotAssignmentChecker,
        private readonly EventEncounterRepository $eventEncounterRepository,
    ) {}

    /**
     * @return TwigFunction[]
     */
    final public function getFunctions(): array
    {
        return [
            new TwigFunction('can_user_take_slot', $this->canUserTakeSlot(...)),
        ];
    }

    /**
     * @throws EntityNotFoundException
     */
    final public function canUserTakeSlot(int $eventEncounterId): bool
    {
        $eventEncounter = $this->eventEncounterRepository->find($eventEncounterId);

        if (!$eventEncounter) {
            throw new EntityNotFoundException(sprintf('Event encounter with id %d not found', $eventEncounterId));
        }

        return $this->slotAssignmentChecker->checkIfUserCanTakeSlot($eventEncounter);
    }
}
