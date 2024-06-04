<?php

namespace App\Controller\GuildEvent;

use App\Builder\GraidAttendanceTableDataBuilder;
use App\Checker\EventManagementPermission\EventManagementPermissionChecker;
use App\Checker\EventParticipationPermission\EventParticipationPermissionChecker;
use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Enum\RolesEnum;
use App\Form\GuildEvent\AttendBackupType;
use App\Service\GuildEvent\EventAttendanceManager;
use App\Service\GuildEvent\PlayerSlotManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::OLD_MEMBER->value)]
#[Route('/attendance', name: 'event_attendance_')]
class EventAttendanceController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface              $entityManager,
        private readonly PlayerSlotManager                   $playerSlotManager,
        private readonly EventAttendanceManager              $eventAttendanceManager,
        private readonly EventManagementPermissionChecker    $eventManagementPermissionChecker,
        private readonly EventParticipationPermissionChecker $eventParticipationPermissionChecker,
    ) {}

    #[Route('/event/{guildEvent}/set_user/{attendanceType}', name: 'add_user', methods: ['GET', 'POST'])]
    final public function addUserToEvent(GuildEvent $guildEvent, string $attendanceType): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$this->eventParticipationPermissionChecker->checkIfUserIsAllowedInEvent($guildEvent)) {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        try {
            $this->eventAttendanceManager->setEventAttendanceForUser(
                $currentUser,
                $guildEvent,
                AttendanceTypeEnum::from($attendanceType)
            );
        } catch (Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('guild_event_show', ['id' => $guildEvent->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/event/{guildEvent}/add_as_backup', name: 'add_user_as_backup', methods: ['GET', 'POST'])]
    final public function addUserAsBackup(Request $request, GuildEvent $guildEvent): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$this->eventParticipationPermissionChecker->checkIfUserIsAllowedInEvent($guildEvent)) {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(AttendBackupType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $eventAttendance = $this->eventAttendanceManager->setEventAttendanceForUser(
                    $currentUser,
                    $guildEvent,
                    AttendanceTypeEnum::BACKUP
                );
                if ($eventAttendance) {
                    $eventAttendance->setComment($form->get('comment')->getData());
                    $this->entityManager->flush();
                }
            } catch (Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->redirectToRoute('guild_event_show', ['id' => $guildEvent->getId()], Response::HTTP_SEE_OTHER);
    }

    #[IsGranted(RolesEnum::TRIAL->value)]
    #[Route('/graid_week', name: 'graid_week', methods: ['GET'])]
    final public function weeklyGuildRaidAttendance(GraidAttendanceTableDataBuilder $tableDataBuilder): Response
    {
        return $this->render('event_attendance/graid_attendance_week.html.twig', [
            'table_data' => $tableDataBuilder->build(),
        ]);
    }

    #[Route('/remove/{id}', name: 'remove', methods: ['GET', 'POST'])]
    final public function removePlayer(Request $request, EventAttendance $eventAttendance): Response
    {
        /** @var GuildEvent $guildEvent */
        $guildEvent = $eventAttendance->getGuildEvent();

        if ($this->eventManagementPermissionChecker->checkIfUserCanManageEvent($guildEvent)
            && $this->isCsrfTokenValid('delete' . $eventAttendance->getId(), $request->getPayload()->get('_token'))
        ) {
            $eventAttendance->setType(AttendanceTypeEnum::UNDEFINED);
            $this->playerSlotManager->emptyAllEventSlotsOfUser($guildEvent, $eventAttendance->getUser());
        }

        return $this->redirectToRoute('guild_event_show', ['id' => $guildEvent->getId()], Response::HTTP_SEE_OTHER);
    }
}
