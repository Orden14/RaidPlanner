<?php

namespace App\Controller\GuildEvent;

use App\Builder\GraidAttendanceTableDataBuilder;
use App\Checker\EventParticipationPermission\EventParticipationPermissionChecker;
use App\Entity\GuildEvent;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Enum\RolesEnum;
use App\Util\GuildEvent\EventAttendanceManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::OLD_MEMBER->value)]
#[Route('/attendance', name: 'event_attendance_')]
class EventAttendanceController extends AbstractController
{
    public function __construct(
        private readonly EventAttendanceManager              $eventAttendanceManager,
        private readonly EventParticipationPermissionChecker $eventParticipationPermissionChecker
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

    #[IsGranted(RolesEnum::TRIAL->value)]
    #[Route('/graid_week', name: 'graid_week', methods: ['GET'])]
    final public function weeklyGuildRaidAttendance(GraidAttendanceTableDataBuilder $tableDataBuilder): Response
    {
        return $this->render('guild_event/event_attendance/graid_attendance_week.html.twig', [
            'table_data' => $tableDataBuilder->build(),
        ]);
    }
}
