<?php

namespace App\Util\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Repository\EventAttendanceRepository;
use App\Service\GuildEvent\SlotService;
use Doctrine\ORM\EntityManagerInterface;

final readonly class EventAttendanceManager
{
    public function __construct(
        private SlotService               $slotService,
        private EntityManagerInterface    $entityManager,
        private EventAttendanceRepository $eventAttendanceRepository,
    ) {}

    public function setEventAttendanceForUser(User $user, GuildEvent $guildEvent, AttendanceTypeEnum $attendanceType): void
    {
        $eventAttendance = $this->eventAttendanceRepository->findOneBy([
            'guildEvent' => $guildEvent,
            'user' => $user
        ]) ?? $this->createAttendanceForUser($user, $guildEvent);

        if ($eventAttendance->getUser() === $user) {
            if ($attendanceType !== AttendanceTypeEnum::PLAYER && $eventAttendance->getType() === AttendanceTypeEnum::PLAYER) {
                $this->slotService->emptyAllEventSlotsOfUser($guildEvent, $user);
            }

            $eventAttendance->setType($attendanceType);
            $this->entityManager->flush();
        }
    }

    private function createAttendanceForUser(User $user, GuildEvent $guildEvent): EventAttendance
    {
        $eventAttendance = (new EventAttendance())
            ->setGuildEvent($guildEvent)
            ->setUser($user);

        $this->entityManager->persist($eventAttendance);
        $this->entityManager->flush();

        return $eventAttendance;
    }
}
