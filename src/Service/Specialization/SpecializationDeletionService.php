<?php

namespace App\Service\Specialization;

use App\Entity\Specialization;
use App\Repository\PlayerSlotRepository;

final readonly class SpecializationDeletionService
{
    public function __construct(
        private PlayerSlotRepository $playerSlotRepository,
    ) {
    }

    public function unlinkPlayerSlots(Specialization $specialization): void
    {
        $playerSlots = $this->playerSlotRepository->findSlotsBySpecialization($specialization);

        foreach ($playerSlots as $playerSlot) {
            $playerSlot->setBuild(null);
        }
    }
}
