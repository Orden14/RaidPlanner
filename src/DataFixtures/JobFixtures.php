<?php


namespace App\DataFixtures;

use App\DataFixtures\util\FileMockUploader;
use App\DTO\Entity\JobDTO;
use App\Factory\JobFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use FilesystemIterator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class JobFixtures extends Fixture
{
    public function __construct(
        private readonly JobFactory $jobFactory,
        private readonly FileMockUploader $fileMockUploader,
        private readonly ParameterBagInterface $parameterBag
    ) {}

    final public function load(ObjectManager $manager): void
    {
        $this->purgeIconDirectory();

        foreach ($this->getJobList() as $jobName => $jobColor) {
            $file = $this->fileMockUploader->mockFileUpload(strtolower($jobName));
            $jobDTO = (new JobDTO())
                ->setName($jobName)
                ->setIcon($file)
                ->setColor($jobColor)
            ;

            $manager->persist($this->jobFactory->create($jobDTO));
        }

        $manager->flush();
    }

    private function purgeIconDirectory(): void
    {
        $files = new FilesystemIterator($this->parameterBag->get('icon_directory'));

        foreach ($files as $file) {
            if ($file->isFile() && $file->getFilename() !== '.gitignore') {
                unlink($file->getPathname());
            }
        }
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
