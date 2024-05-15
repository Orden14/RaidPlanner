<?php

namespace App\EventSubscriber;

use App\Entity\Build;
use App\Entity\BuildMessage;
use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\User;
use App\Entity\UserActivityLog;
use App\Enum\AttendanceTypeEnum;
use App\Enum\DoctrineEventTypeEnum;
use App\Enum\UserActivityLogTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\SecurityBundle\Security;

#[AsDoctrineListener(event: Events::postPersist)]
#[AsDoctrineListener(event: Events::postUpdate)]
#[AsDoctrineListener(event: Events::postRemove)]
final readonly class UserActivitySubscriber
{
    public function __construct(
        private Security $security
    ) {}

    public function postPersist(PostPersistEventArgs $args): void
    {
        $this->logActivity($args, DoctrineEventTypeEnum::POST_PERSIST);
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->logActivity($args, DoctrineEventTypeEnum::POST_UPDATE);
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        $this->logActivity($args, DoctrineEventTypeEnum::POST_REMOVE);
    }

    /**
     * @param LifecycleEventArgs<ObjectManager> $args
     */
    private function logActivity(LifecycleEventArgs $args, DoctrineEventTypeEnum $eventType): void
    {
        $entity = $args->getObject();
        $log = new UserActivityLog();

        if ($entity instanceof User) {
            $log->setType(UserActivityLogTypeEnum::USER);
            $message = $this->getUserLogMessage($entity, $eventType);
        } elseif ($entity instanceof Build) {
            $log->setType(UserActivityLogTypeEnum::BUILD);
            $message = $this->getBuildLogMessage($entity, $eventType);
        } elseif ($entity instanceof BuildMessage) {
            $log->setType(UserActivityLogTypeEnum::BUILD);
            $message = $this->getBuildMessageLogMessage($entity, $eventType);
        } elseif ($entity instanceof GuildEvent) {
            $log->setType(UserActivityLogTypeEnum::GUILD_EVENT);
            $message = $this->getGuildEventLogMessage($entity, $eventType);
        } elseif ($entity instanceof EventAttendance) {
            $log->setType(UserActivityLogTypeEnum::GUILD_EVENT);
            $message = $this->getEventAttendanceLogMessage($entity, $eventType);
        } else {
            return;
        }

        $entityManager = $args->getObjectManager();

        $log->setLogMessage($message);
        $entityManager->persist($log);
        $entityManager->flush();
    }

    private function getUserLogMessage(User $user, DoctrineEventTypeEnum $eventType): string
    {
        /** @var ?User $currentUser */
        $currentUser = $this->security->getUser();
        $currentUserName = $currentUser?->getUsername() ?? "Unknown";

        return match ($eventType) {
            DoctrineEventTypeEnum::POST_PERSIST => "Nouvel utilisateur : {$user->getUsername()}",
            DoctrineEventTypeEnum::POST_UPDATE => "Mise à jour de l'utilisateur {$user->getUsername()} par $currentUserName",
            DoctrineEventTypeEnum::POST_REMOVE => "Suppression de l'utilisateur {$user->getUsername()} par $currentUserName"
        };
    }

    private function getBuildLogMessage(Build $build, DoctrineEventTypeEnum $eventType): string
    {
        /** @var ?User $currentUser */
        $currentUser = $this->security->getUser();
        $currentUserName = $currentUser?->getUsername() ?? "Unknown";

        return match ($eventType) {
            DoctrineEventTypeEnum::POST_PERSIST => "Nouveau build {$build->getName()} créé par {$build->getAuthor()?->getUsername()}",
            DoctrineEventTypeEnum::POST_UPDATE => "Le build {$build->getName()} a été modifié par $currentUserName",
            DoctrineEventTypeEnum::POST_REMOVE => "Le build {$build->getName()} a été supprimé par $currentUserName"
        };
    }

    private function getBuildMessageLogMessage(BuildMessage $message, DoctrineEventTypeEnum $eventType): string
    {
        /** @var ?User $currentUser */
        $currentUser = $this->security->getUser();
        $currentUserName = $currentUser?->getUsername() ?? "Unknown";

        return match ($eventType) {
            DoctrineEventTypeEnum::POST_PERSIST => "Build {$message->getBuild()?->getName()} : nouveau message par {$message->getAuthor()?->getUsername()}",
            DoctrineEventTypeEnum::POST_UPDATE => "Build {$message->getBuild()?->getName()} : $currentUserName a modifié un message",
            DoctrineEventTypeEnum::POST_REMOVE => "Build {$message->getBuild()?->getName()} : $currentUserName a supprimé un message"
        };
    }

    private function getGuildEventLogMessage(GuildEvent $event, DoctrineEventTypeEnum $eventType): string
    {
        /** @var ?User $currentUser */
        $currentUser = $this->security->getUser();
        $currentUserName = $currentUser?->getUsername() ?? "Unknown";

        return match ($eventType) {
            DoctrineEventTypeEnum::POST_PERSIST => "Nouvel événement de guilde : {$event->getTitle()}",
            DoctrineEventTypeEnum::POST_UPDATE => "Mise à jour de l'événement de guilde {$event->getTitle()} par $currentUserName",
            DoctrineEventTypeEnum::POST_REMOVE => "Suppression de l'événement de guilde {$event->getTitle()} par $currentUserName"
        };
    }

    private function getEventAttendanceLogMessage(EventAttendance $attendance, DoctrineEventTypeEnum $eventType): string
    {
        /** @var ?User $currentUser */
        $currentUser = $this->security->getUser();
        $currentUserName = $currentUser?->getUsername() ?? "Unknown";

        return match ($eventType) {
            DoctrineEventTypeEnum::POST_PERSIST => $this->getEventAttendancePersistMessage($attendance, $currentUserName),
            DoctrineEventTypeEnum::POST_UPDATE => "Mise à jour de la participation de {$attendance->getUser()?->getUsername()} à l'événement {$attendance->getGuildEvent()?->getTitle()} : {$attendance->getType()->value}",
            DoctrineEventTypeEnum::POST_REMOVE => "Suppression de la participation à l'événement {$attendance->getGuildEvent()?->getTitle()} de {$attendance->getUser()?->getUsername()} par $currentUserName"
        };
    }

    private function getEventAttendancePersistMessage(EventAttendance $attendance, string $currentUserName): string
    {
        return match ($attendance->getType()) {
            AttendanceTypeEnum::PLAYER => "Nouvelle participation à l'événement {$attendance->getGuildEvent()?->getTitle()} par {$attendance->getUser()?->getUsername()}",
            AttendanceTypeEnum::BACKUP => "{$attendance->getUser()?->getUsername()} est ajouté en tant que backup à l'événement {$attendance->getGuildEvent()?->getTitle()}",
            AttendanceTypeEnum::ABSENT => "{$attendance->getUser()?->getUsername()} est ajouté en tant qu'absent à l'événement {$attendance->getGuildEvent()?->getTitle()}",
            default => "$currentUserName a ajouté son status pour l'évènement {$attendance->getGuildEvent()?->getTitle()}"
        };
    }
}
