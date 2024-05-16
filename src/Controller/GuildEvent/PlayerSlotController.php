<?php

namespace App\Controller\GuildEvent;

use App\Checker\SlotAssignmentPermission\SlotAssignmentPermissionChecker;
use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Enum\RolesEnum;
use App\Util\GuildEvent\PlayerSlotHtmlGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::OLD_MEMBER->value)]
#[Route('/guild-event/player-slot', name: 'player_slot_')]
class PlayerSlotController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface          $entityManager,
        private readonly PlayerSlotHtmlGenerator         $playerSlotHtmlGenerator,
        private readonly SlotAssignmentPermissionChecker $slotAssignmentPermissionChecker,
    ) {}

    #[Route('/event/{eventBattle}/slot/assign/{playerSlot}', name: 'assign', methods: ['GET', 'POST'])]
    final public function assignToSlot(EventBattle $eventBattle, PlayerSlot $playerSlot): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$this->slotAssignmentPermissionChecker->checkIfUserCanTakeSlot($eventBattle)) {
            return new JsonResponse("Erreur : vous ne pouvez pas modifier ce slot", Response::HTTP_FORBIDDEN);
        }

        $playerSlot->setPlayer($currentUser);
        $this->entityManager->flush();

        return new JsonResponse($this->playerSlotHtmlGenerator->generateTakenSlotHtml($currentUser, $playerSlot), Response::HTTP_OK);
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
