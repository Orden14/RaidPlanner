<?php

namespace App\Controller\GuildEvent;

use App\Entity\GuildEventRelation\EventEncounter;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Enum\RolesEnum;
use App\Util\GuildEvent\NonActiveSlotManager;
use App\Util\GuildEvent\PlayerSlotChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::OLD_MEMBER->value)]
#[Route('/guild-event/player-slot', name: 'player_slot_')]
class PlayerSlotController extends AbstractController
{
    public function __construct(
        private readonly PlayerSlotChecker $playerSlotChecker,
        private readonly EntityManagerInterface $entityManager,
        private readonly NonActiveSlotManager $nonActiveSlotManager
    ) {}

    #[Route('/event/{eventEncounter}/slot/assign/{playerSlot}', name: 'assign', methods: ['GET', 'POST'])]
    final public function assignToSlot(EventEncounter $eventEncounter, PlayerSlot $playerSlot): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$this->playerSlotChecker->isUserAllowedToSignUp($eventEncounter)) {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $this->nonActiveSlotManager->manageNonActiveSlotsForUser($eventEncounter->getGuildEvent());
        $playerSlot->setPlayer($currentUser);
        $this->entityManager->flush();


        return $this->redirectToRoute('guild_event_show', ['id' => $eventEncounter->getGuildEvent()?->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/slot/free/{playerSlot}', name: 'free', methods: ['GET'])]
    final public function freeSlot(PlayerSlot $playerSlot): Response
    {
        if ($this->playerSlotChecker->isUserAllowedToFreeSlot($playerSlot)) {
            $playerSlot->setPlayer(null);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute(
            'guild_event_show',
            ['id' => $playerSlot->getEventEncounter()?->getGuildEvent()?->getId()],
            Response::HTTP_SEE_OTHER
        );
    }
}
