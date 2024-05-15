<?php

namespace App\DataFixtures;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventEncounter;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Enum\InstanceTypeEnum;
use App\Repository\BuildRepository;
use App\Repository\EncounterRepository;
use App\Repository\UserRepository;
use App\Util\GuildEvent\EventAttendanceManager;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GuildEventFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepository         $userRepository,
        private readonly BuildRepository        $buildRepository,
        private readonly EncounterRepository    $encounterRepository,
        private readonly EventAttendanceManager $eventAttendanceManager,
    ) {}

    final public function load(ObjectManager $manager): void
    {
        $this->createGuildEvent($manager);
    }

    private function createGuildEvent(ObjectManager $manager): void
    {
        $guildEvent = (new GuildEvent())
            ->setTitle('Test event')
            ->setStart((new DateTime())->setTime(10, 0))
            ->setEnd((new DateTime())->setTime(15, 0))
            ->setType(InstanceTypeEnum::RAID);

        $manager->persist($guildEvent);

        $users = [];
        for ($i = 0; $i < InstanceTypeEnum::getMaxPlayersByType($guildEvent->getType()); $i++) {
            /** @var User $user */
            $user = $this->userRepository->find($i +1);

            if ($i < 6) {
                $this->eventAttendanceManager->setEventAttendanceForUser($user, $guildEvent, AttendanceTypeEnum::PLAYER);
            } elseif ($i < 8) {
                $this->eventAttendanceManager->setEventAttendanceForUser($user, $guildEvent, AttendanceTypeEnum::BACKUP);
            } else {
                $this->eventAttendanceManager->setEventAttendanceForUser($user, $guildEvent, AttendanceTypeEnum::ABSENT);
            }

            $users[] = $user;
        }

        for ($y = 0; $y < 2; $y++) {
            $eventEncounter = (new EventEncounter())
                ->setGuildEvent($guildEvent)
                ->setEncounter($this->encounterRepository->find($y + 1));

            $manager->persist($eventEncounter);

            for ($i = 0; $i < InstanceTypeEnum::getMaxPlayersByType($guildEvent->getType()); $i++) {
                $eventSlot = (new PlayerSlot())->setEventEncounter($eventEncounter);

                $eventSlot->setBuild($this->buildRepository->find($i + 1));

                if ($i === 0) {
                    $eventSlot->setTank(true);
                }

                if ($i <= 4) {
                    $eventSlot->setPlayer($users[$i]);
                }

                $manager->persist($eventSlot);
            }
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    final public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            BuildFixtures::class,
            EncounterFixtures::class
        ];
    }
}
