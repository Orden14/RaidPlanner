<?php

namespace App\DataFixtures;

use App\Entity\Encounter;
use App\Repository\InstanceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EncounterFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly InstanceRepository $instanceRepository
    ) {}

    final public function load(ObjectManager $manager): void
    {
        foreach ($this->getEncountersList() as $instanceTag => $encounters) {
            $instance = $this->instanceRepository->findOneBy(['tag' => $instanceTag]);

            foreach ($encounters as $encounter) {
                $encounter = (new Encounter())
                    ->setName($encounter)
                    ->setInstance($instance);

                $manager->persist($encounter);
            }
        }

        $manager->flush();
    }

    /**
     * @return array<string, string[]>
     */
    private function getEncountersList(): array
    {
        return [
            'w1' => [
                'Vale Guardian',
                'Gorseval the Multifarious',
                'Sabetha the Saboteur',
            ],
            'w2' => [
                'Slothasor',
                'Trio',
                'Matthias Gabrel',
            ],
            'w3' => [
                'Escort',
                'Keep Construct',
                'Twisted Castle',
                'Xera',
            ],
            'w4' => [
                'Cairn the Indomitable',
                'Mursaat Overseer',
                'Samarog',
                'Deimos',
            ],
            'w5' => [
                'Soulless Horror',
                'River of Souls',
                'Statues of Grenth',
                'Dhuum',
            ],
            'w6' => [
                'Conjured Amalgamate',
                'Twin Largos',
                'Qadim',
            ],
            'w7' => [
                'Gate',
                'Cardinal Adina',
                'Cardinal Sabir',
                'Qadim the Peerless',
            ]
        ];
    }

    /**
     * @return string[]
     */
    final public function getDependencies(): array
    {
        return [
            InstanceFixtures::class,
        ];
    }
}
