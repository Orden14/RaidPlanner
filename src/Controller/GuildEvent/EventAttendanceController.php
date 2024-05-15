<?php

namespace App\Controller\GuildEvent;

use App\Checker\EventParticipationPermission\EventParticipationChecker;
use App\Entity\GuildEvent;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Enum\RolesEnum;
use App\Util\GuildEvent\EventAttendanceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::OLD_MEMBER->value)]
#[Route('/attendance', name: 'event_attendance_')]
class EventAttendanceController extends AbstractController
{
    private User $currentUser;

    public function __construct(
        private readonly EventAttendanceManager    $eventAttendanceManager,
        private readonly EventParticipationChecker $eventParticipationChecker
    )
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->currentUser = $user;
    }

    #[Route('/event/{guildEvent}/set_player', name: 'add_player', methods: ['POST'])]
    final public function addUserToPlayers(GuildEvent $guildEvent): Response
    {
        if (!$this->eventParticipationChecker->checkIfUserIsAllowedInEvent($guildEvent)) {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $this->eventAttendanceManager->setEventAttendanceForUser($this->currentUser, $guildEvent, AttendanceTypeEnum::PLAYER);

        return $this->redirectToRoute('guild_event_show', ['id' => $guildEvent->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/event/{guildEvent}/set_backup', name: 'add_backup', methods: ['POST'])]
    final public function addUserToBackups(GuildEvent $guildEvent): Response
    {
        if (!$this->eventParticipationChecker->checkIfUserIsAllowedInEvent($guildEvent)) {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $this->eventAttendanceManager->setEventAttendanceForUser($this->currentUser, $guildEvent, AttendanceTypeEnum::BACKUP);


        return $this->redirectToRoute('guild_event_show', ['id' => $guildEvent->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/event/{guildEvent}/set_absent', name: 'add_absent', methods: ['POST'])]
    final public function addUserToAbsent(GuildEvent $guildEvent): Response
    {
        if (!$this->eventParticipationChecker->checkIfUserIsAllowedInEvent($guildEvent)) {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $this->eventAttendanceManager->setEventAttendanceForUser($this->currentUser, $guildEvent, AttendanceTypeEnum::ABSENT);

        return $this->redirectToRoute('guild_event_show', ['id' => $guildEvent->getId()], Response::HTTP_SEE_OTHER);
    }
}
