<?php

namespace App\DataFixtures;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\GuildEventEncounter;
use App\Entity\GuildEventRelation\GuildEventSlot;
use App\Enum\GuildEventTypeEnum;
use App\Repository\EncounterRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GuildEventFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly EncounterRepository $encounterRepository
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

        $encounters = $this->encounterRepository->findAll();
        $guildEventEcounter = (new GuildEventEncounter())
            ->setGuildEvent($guildEvent)
            ->setEncounter(reset($encounters));

        $manager->persist($guildEventEcounter);

        for ($i = 0; $i < GuildEventTypeEnum::getMaxPlayersByType($guildEvent->getType()); $i++) {
            $guildEventSlot = (new GuildEventSlot())
                ->setGuildEvent($guildEvent)
                ->setGuildEventEncounter($guildEventEcounter);

            $manager->persist($guildEventSlot);
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
            EncounterFixtures::class
        ];
    }
}
