<?php


namespace App\DataFixtures;

use App\DataFixtures\util\FileMockUploader;
use App\DTO\Entity\SpecializationDTO;
use App\Factory\SpecializationFactory;
use App\Repository\JobRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Exception\UnexpectedValueException;

class SpecializationFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly JobRepository $jobRepository,
        private readonly FileMockUploader $fileMockUploader,
        private readonly SpecializationFactory $specializationFactory
    ){ }

    final public function load(ObjectManager $manager): void
    {
        foreach ($this->getSpecializationList() as $jobName => $specializations) {
            $this->createSpecializationsByJob($jobName, $specializations, $manager);
        }

        $manager->flush();
    }


    /**
     * @param string[] $specializations
     *
     * @throws UnexpectedValueException
     */
    private function createSpecializationsByJob(string $jobName, array $specializations, ObjectManager $manager): void
    {
        $job = $this->jobRepository->findOneBy(['name' => $jobName]);

        if ($job === null) {
            throw new UnexpectedValueException('Expected entity of type Job, but got null.');
        }

        foreach ($specializations as $specializationName) {
            $file = $this->fileMockUploader->mockFileUpload(strtolower($specializationName));
            $specializationDTO = (new SpecializationDTO())
                ->setName($specializationName)
                ->setJob($job)
                ->setIcon($file)
            ;

            $manager->persist($this->specializationFactory->create($specializationDTO));
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
