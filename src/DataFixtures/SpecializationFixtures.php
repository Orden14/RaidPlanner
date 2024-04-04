<?php


namespace App\DataFixtures;

use App\Entity\Specialization;
use App\Repository\JobRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SpecializationFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly JobRepository $jobRepository
    ){ }

    final public function load(ObjectManager $manager): void
    {
        foreach ($this->getSpecializationList() as $jobName => $specializations) {
            $this->createSpecializationByJob($jobName, $specializations, $manager);
        }

        $manager->flush();
    }


    /**
     * @param string[] $specializations
     */
    private function createSpecializationByJob(string $jobName, array $specializations, ObjectManager $manager): void
    {
        $job = $this->jobRepository->findOneBy(['name' => $jobName]);

        foreach ($specializations as $specializationName) {
            $specialization = new Specialization();
            $specialization->setName($specializationName)
                ->setJob($job)
                ->setIcon(strtolower($specializationName) . '.png')
            ;

            $manager->persist($specialization);
        }

        $manager->flush();
    }

    /**
     * @return array<string, string[]>
     */
    private function getSpecializationList(): array
    {
        return [
            'Warrior' => [
                'Berserker',
                'Bladesworn',
                'Spellbreaker',
            ],
            'Guardian' => [
                'Dragonhunter',
                'Firebrand',
                'Willbender',
            ],
            'Revenant' => [
                'Herald',
                'Renegade',
                'Vindicator',
            ],
            'Engineer' => [
                'Scrapper',
                'Holosmith',
                'Mechanist',
            ],
            'Ranger' => [
                'Druid',
                'Soulbeast',
                'Untamed',
            ],
            'Thief' => [
                'Daredevil',
                'Deadeye',
                'Specter',
            ],
            'Elementalist' => [
                'Tempest',
                'Weaver',
                'Catalyst',
            ],
            'Mesmer' => [
                'Chronomancer',
                'Mirage',
                'Virtuoso',
            ],
            'Necromancer' => [
                'Reaper',
                'Scourge',
                'Harbinger',
            ],
        ];
    }

    /**
     * @return string[]
     */
    final public function getDependencies(): array
    {
        return [
            JobFixtures::class
        ];
    }
}
