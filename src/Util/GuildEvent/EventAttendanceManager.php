<?php

namespace App\Util\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\User;
use App\Repository\EventAttendanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class EventAttendanceManager
{
    public function __construct(
        private Security                  $security,
        private EntityManagerInterface    $entityManager,
        private EventAttendanceRepository $eventAttendanceRepository,
    ) {}

    public function removeNonActiveAttendanceForUser(GuildEvent $guildEvent): void
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $eventAttendances = $this->eventAttendanceRepository->findNonActiveAttendanceForPlayer($guildEvent, $currentUser);
        foreach ($eventAttendances as $eventAttendance) {
            if ($eventAttendance->getUser() === $currentUser) {
                $this->entityManager->remove($eventAttendance);
            }
        }

        $this->entityManager->flush();
    }
}
