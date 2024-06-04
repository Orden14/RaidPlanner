<?php

namespace App\Controller\GuildEvent;

use App\Checker\SlotAssignmentPermission\SlotAssignmentPermissionChecker;
use App\Checker\SlotManagementPermission\SlotManagementPermissionChecker;
use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Enum\RolesEnum;
use App\Service\GuildEvent\EventAttendanceManagementService;
use App\Service\GuildEvent\EventAttendanceDataService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::OLD_MEMBER->value)]
#[Route('/guild-event/player-slot', name: 'player_slot_')]
class PlayerSlotController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface           $entityManager,
        private readonly EventAttendanceDataService       $eventAttendanceService,
        private readonly SlotAssignmentPermissionChecker  $slotAssignmentPermissionChecker,
        private readonly SlotManagementPermissionChecker  $slotManagementPermissionChecker,
        private readonly EventAttendanceManagementService $eventAttendanceManagementService,
    ) {}

    #[Route('/battle/{eventBattle}/slot/assign/{playerSlot}', name: 'assign', methods: ['GET'])]
    final public function assignToSlot(Request $request, EventBattle $eventBattle, PlayerSlot $playerSlot): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        /** @var GuildEvent $guildEvent */
        $guildEvent = $eventBattle->getGuildEvent();

        $request->getSession()->set('eventBattleId', $eventBattle->getId());

        if (!$this->slotAssignmentPermissionChecker->checkIfUserCanTakeSlot($eventBattle)) {
            return new JsonResponse("Vous n'êtes pas autorisé à faire cette action.", Response::HTTP_FORBIDDEN);
        }

        if (!$this->eventAttendanceService->isUserAttendingAsPlayer($guildEvent, $currentUser)) {
            $this->eventAttendanceManagementService->setEventAttendanceForUser($currentUser, $guildEvent, AttendanceTypeEnum::PLAYER);
        }

        $playerSlot->setPlayer($currentUser);
        $this->entityManager->flush();

        return new JsonResponse('Slot assigné.', Response::HTTP_OK);
    }

    #[Route('/slot/free/{playerSlot}', name: 'free', methods: ['GET'])]
    final public function freeSlot(Request $request, PlayerSlot $playerSlot): JsonResponse
    {
        $request->getSession()->set('eventBattleId', $playerSlot->getEventBattle()?->getId());

        if (!$this->slotManagementPermissionChecker->checkIfUserCanManageSlot($playerSlot)) {
            return new JsonResponse("Vous n'êtes pas autorisé à faire cette action.", Response::HTTP_FORBIDDEN);
        }

        $playerSlot->setPlayer(null);
        $this->entityManager->flush();

        return new JsonResponse('Slot libéré', Response::HTTP_OK);
    }
}
