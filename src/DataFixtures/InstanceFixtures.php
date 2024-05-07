<?php

namespace App\DataFixtures;

use App\Entity\Instance;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InstanceFixtures extends Fixture
{
    final public function load(ObjectManager $manager): void
    {
        foreach ($this->getRaidWingsList() as $tag => $name) {
            $instance = (new Instance())
                ->setName($name)
                ->setTag($tag);

            $manager->persist($instance);
        }

        $manager->flush();
    }

    /**
     * @return array<string, string>
     */
    private function getRaidWingsList(): array
    {
        return [
            'w1' => 'Spirit Vale',
            'w2' => 'Salvation Pass',
            'w3' => 'Stronghold of the Faithful',
            'w4' => 'Bastion of the Penitent',
            'w5' => 'Hall of Chains',
            'w6' => 'Mythwright Gambit',
            'w7' => 'The Key of Ahdashim'
        ];
    }
}
