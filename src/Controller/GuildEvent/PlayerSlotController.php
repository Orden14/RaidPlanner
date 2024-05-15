<?php

namespace App\Controller\GuildEvent;

use App\Checker\SlotAssignmentPermission\SlotAssignmentPermissionChecker;
use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Enum\RolesEnum;
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
        private readonly EntityManagerInterface          $entityManager,
        private readonly SlotAssignmentPermissionChecker $slotAssignmentPermissionChecker,
    ) {}

    #[Route('/event/{eventBattle}/slot/assign/{playerSlot}', name: 'assign', methods: ['GET', 'POST'])]
    final public function assignToSlot(EventBattle $eventBattle, PlayerSlot $playerSlot): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$this->slotAssignmentPermissionChecker->checkIfUserCanTakeSlot($eventBattle)) {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $playerSlot->setPlayer($currentUser);
        $this->entityManager->flush();

        return $this->redirectToRoute('guild_event_show', ['id' => $eventBattle->getGuildEvent()?->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/slot/free/{playerSlot}', name: 'free', methods: ['GET'])]
    final public function freeSlot(PlayerSlot $playerSlot): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($this->isGranted(RolesEnum::ADMIN->value) || $currentUser === $playerSlot->getPlayer()) {
            $playerSlot->setPlayer(null);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute(
            'guild_event_show',
            ['id' => $playerSlot->getEventBattle()?->getGuildEvent()?->getId()],
            Response::HTTP_SEE_OTHER
        );
    }
}
