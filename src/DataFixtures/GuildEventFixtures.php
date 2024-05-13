<?php

namespace App\DataFixtures;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventEncounter;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Enum\GuildEventTypeEnum;
use App\Repository\BuildRepository;
use App\Repository\EncounterRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GuildEventFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly BuildRepository $buildRepository,
        private readonly EncounterRepository $encounterRepository,
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
            ->setType(GuildEventTypeEnum::RAID);

        $manager->persist($guildEvent);

        $eventEncounter = (new EventEncounter())
            ->setGuildEvent($guildEvent)
            ->setEncounter($this->encounterRepository->find(1));

        $manager->persist($eventEncounter);

        for ($i = 0; $i < GuildEventTypeEnum::getMaxPlayersByType($guildEvent->getType()); $i++) {
            $eventSlot = (new PlayerSlot())->setEventEncounter($eventEncounter);

            if ($i < 5) {
                $eventSlot->setPlayer($this->userRepository->find($i))
                    ->setBuild($this->buildRepository->find($i));
            }

            $manager->persist($eventSlot);
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
