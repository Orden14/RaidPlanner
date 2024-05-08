<?php

namespace App\EventSubscriber;

use App\Entity\Build;
use App\Entity\BuildMessage;
use App\Entity\User;
use App\Entity\UserActivityLog;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectManager;

#[AsDoctrineListener(event: Events::postPersist)]
#[AsDoctrineListener(event: Events::postUpdate)]
#[AsDoctrineListener(event: Events::postRemove)]
final readonly class UserActivitySubscriber
{
    public function postPersist(PostPersistEventArgs $args): void
    {
        $this->logActivity('added', $args);
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->logActivity('updated', $args);
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        $this->logActivity('removed', $args);
    }

    /**
     * @param LifecycleEventArgs<ObjectManager> $args
     */
    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        if ($entity instanceof User || $entity instanceof Build || $entity instanceof BuildMessage) {
            $log = new UserActivityLog();
            $log->setLogMessage("nouveau log {$action}");
            $log->setDate(new DateTime());

            $entityManager->persist($log);
            $entityManager->flush();
        }
    }
}
