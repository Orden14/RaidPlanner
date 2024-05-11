<?php

namespace App\DataFixtures;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\GuildEventSlot;
use App\Enum\GuildEventTypeEnum;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GuildEventFixtures extends Fixture
{
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
            ->setType(GuildEventTypeEnum::RAID)
            ->setMaxPlayers();

        $manager->persist($guildEvent);

        for ($i = 0; $i < $guildEvent->getMaxPlayers(); $i++) {
            $guildEventSlot = (new GuildEventSlot())
                ->setGuildEvent($guildEvent);

            $manager->persist($guildEventSlot);
        }

        $manager->flush();
    }
}
