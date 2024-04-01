<?php


namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobFixtures extends Fixture
{
    final public function load(ObjectManager $manager): void
    {
        foreach ($this->getJobList() as $jobName => $jobColor) {
            $manager->persist($this->generateJob($jobName, $jobColor));
        }

        $manager->flush();
    }


    private function generateJob(string $jobName, string $jobColor): Job
    {
        return (new Job())
            ->setName($jobName)
            ->setIcon(strtolower($jobName) . '.png')
            ->setColor($jobColor)
        ;
    }

    /**
     * @return array<string, string>
     */
    private function getJobList(): array
    {
        return [
            'Elementalist' => '#f54251',
            'Engineer' => '#ce7f4b',
            'Guardian' => '#5fbcd3',
            'Mesmer' => '#d42aff',
            'Necromancer' => '#22bc72',
            'Ranger' => '#95c34a',
            'Revenant' => '#860000',
            'Thief' => '#89676d',
            'Warrior' => '#c2a056'
        ];
    }
}
