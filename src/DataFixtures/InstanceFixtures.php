<?php

namespace App\DataFixtures;

use App\Entity\Instance;
use App\Enum\InstanceTypeEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InstanceFixtures extends Fixture
{
    final public function load(ObjectManager $manager): void
    {
        foreach ($this->getInstanceList() as $name => [$tag, $type]) {
            $instance = (new Instance())
                ->setName($name)
                ->setTag($tag)
                ->setType($type);

            $manager->persist($instance);
        }

        $manager->flush();
    }

    /**
     * @return array<string, array<int, string|InstanceTypeEnum>>
     */
    private function getInstanceList(): array
    {
        return [
            'Spirit Vale' => ['w1', InstanceTypeEnum::RAID],
            'Salvation Pass' => ['w2', InstanceTypeEnum::RAID],
            'Stronghold of the Faithful' => ['w3', InstanceTypeEnum::RAID],
            'Bastion of the Penitent' => ['w4', InstanceTypeEnum::RAID],
            'Hall of Chains' => ['w5', InstanceTypeEnum::RAID],
            'Mythwright Gambit' => ['w6', InstanceTypeEnum::RAID],
            'The Key of Ahdashim' => ['w7', InstanceTypeEnum::RAID],
            'Fractal of the Mists' => ['fractal', InstanceTypeEnum::FRACTAL],
            'Strike mission' => ['strike', InstanceTypeEnum::STRIKE],
            'Dungeon' => ['donjon', InstanceTypeEnum::DUNGEON],
        ];
    }
}
