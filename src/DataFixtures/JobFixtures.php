<?php


namespace App\DataFixtures;

use App\DTO\Entity\JobDTO;
use App\Entity\Job;
use App\Factory\JobFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class JobFixtures extends Fixture
{
    public function __construct(
        private readonly JobFactory $jobFactory
    ) {}

    final public function load(ObjectManager $manager): void
    {
        foreach ($this->getJobList() as $jobName => $jobColor) {
            $filePath = __DIR__ . '/icon/' . strtolower($jobName) . '.png';
            $file = new UploadedFile($filePath, basename($filePath), 'image/png');
            $jobDTO = (new JobDTO())
                ->setName($jobName)
                ->setIcon($file)
                ->setColor($jobColor)
            ;

            $manager->persist($this->jobFactory->create($jobDTO));
        }

        $manager->flush();
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
