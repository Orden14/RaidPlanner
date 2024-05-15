<?php

namespace App\Service\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventEncounter;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Repository\PlayerSlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

final readonly class SlotService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PlayerSlotRepository   $playerSlotRepository,
    ) {}

    /**
     * @param PlayerSlot[] $playerSlots
     */
    public function createSlotsForEncounter(EventEncounter $eventEncounter, array $playerSlots): void
    {
        foreach ($playerSlots as $playerSlot) {
            $playerSlot->setEventEncounter($eventEncounter);
            $this->entityManager->persist($playerSlot);
        }

        $this->entityManager->flush();
    }

    /**
     * @return PlayerSlot[]
     */
    public function getPlayerSlotsFromForm(FormInterface $form): array
    {
        return array_filter(
            array_map(static fn($form) => $form->getData(), $form->all()),
            static fn($data) => $data instanceof PlayerSlot
        );
    }

    public function emptyAllEventSlotsOfUser(GuildEvent $guildEvent, User $user): void
    {
        $slots = $this->playerSlotRepository->findSlotsByEventsForUser($guildEvent, $user);
        foreach ($slots as $slot) {
            $slot->setPlayer(null);
        }

        $this->entityManager->flush();
    }
}
