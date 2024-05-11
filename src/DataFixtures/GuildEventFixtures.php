<?php

namespace App\DataFixtures;

use App\Entity\GuildEvent;
use App\Enum\GuildEventTypeEnum;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GuildEventFixtures extends Fixture
{
    final public function load(ObjectManager $manager): void
    {

    }
}
