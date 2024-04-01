<?php

namespace App\DataFixtures;

use App\Enum\GuildEventTypeEnum;
use DateTime;
use App\Entity\GuildEvent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GuildEventFixtures extends Fixture
{
    final public function load(ObjectManager $manager): void
    {
        $now = new DateTime();

        $pastGuildEvent = new GuildEvent();
        $pastGuildEvent->setTitle('Projet wingman VG');
        $pastGuildEvent->setStart((clone $now)->setISODate($now->format('o'), $now->format('W') - 1, 4)->setTime(20, 0));
        $pastGuildEvent->setEnd((clone $now)->setISODate($now->format('o'), $now->format('W') - 1, 4)->setTime(23, 45));
        $pastGuildEvent->setType(GuildEventTypeEnum::GUILDRAID);
        $manager->persist($pastGuildEvent);

        $guildEventOne = new GuildEvent();
        $guildEventOne->setTitle('Raid des ptiots');
        $guildEventOne->setStart((clone $now)->setISODate($now->format('o'), $now->format('W'), 1)->setTime(14, 0));
        $guildEventOne->setEnd((clone $now)->setISODate($now->format('o'), $now->format('W'), 1)->setTime(18, 30));
        $guildEventOne->setType(GuildEventTypeEnum::RAID);
        $guildEventOne->setColor('#3bdb7b');
        $manager->persist($guildEventOne);

        $guildEventTwo = new GuildEvent();
        $guildEventTwo->setTitle('GRAID Sabir');
        $guildEventTwo->setStart((clone $now)->setISODate($now->format('o'), $now->format('W'), 1)->setTime(21, 0));
        $guildEventTwo->setEnd((clone $now)->setISODate($now->format('o'), $now->format('W'), 1)->setTime(23, 15));
        $guildEventTwo->setType(GuildEventTypeEnum::GUILDRAID);
        $manager->persist($guildEventTwo);

        $guildEventThree = new GuildEvent();
        $guildEventThree->setTitle('Guild Raid Dhuum');
        $guildEventThree->setStart((clone $now)->setISODate($now->format('o'), $now->format('W'), 5)->setTime(21, 0));
        $guildEventThree->setEnd((clone $now)->setISODate($now->format('o'), $now->format('W'), 5)->setTime(23, 0));
        $guildEventThree->setType(GuildEventTypeEnum::GUILDRAID);
        $manager->persist($guildEventThree);

        $futureGuildEvent = new GuildEvent();
        $futureGuildEvent->setTitle('Projet world record CA');
        $futureGuildEvent->setStart((clone $now)->setISODate($now->format('o'), $now->format('W') + 1, 2)->setTime(20, 0));
        $futureGuildEvent->setEnd((clone $now)->setISODate($now->format('o'), $now->format('W') + 1, 2)->setTime(23, 45));
        $futureGuildEvent->setType(GuildEventTypeEnum::GUILDRAID);
        $futureGuildEvent->setColor('#db8b3b');
        $manager->persist($futureGuildEvent);

        $manager->flush();
    }

}
