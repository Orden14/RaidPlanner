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
            new TwigFunction('does_user_already_have_slot', $this->doesUserAlreadyHaveSlot(...)),
        ];
    }

    /**
     * @throws EntityNotFoundException
     */
    final public function doesUserAlreadyHaveSlot(int $eventEncounterId): bool
    {
        $eventEncounter = $this->eventEncounterRepository->find($eventEncounterId);

        if (!$eventEncounter) {
            throw new EntityNotFoundException(sprintf('Event encounter with id %d not found', $eventEncounterId));
        }

        return $this->slotAssignmentChecker->checkIfUserCanTakeSlot($eventEncounter);
    }
}
