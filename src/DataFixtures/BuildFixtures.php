<?php

namespace App\DataFixtures;

use App\Entity\Build;
use App\Entity\BuildCategory;
use App\Entity\Specialization;
use App\Entity\User;
use App\Enum\BuildStatusEnum;
use App\Repository\BuildCategoryRepository;
use App\Repository\SpecializationRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class BuildFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly BuildCategoryRepository $buildCategoryRepository,
        private readonly SpecializationRepository $specializationRepository
    ) {}

    final public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = $this->userRepository->findAll();
        $categories = $this->buildCategoryRepository->findAll();
        $specializations = $this->specializationRepository->findAll();

        for ($i = 0; $i < 40; $i++) {
            $manager->persist($this->generateBuild(
                $faker,
                $users[array_rand($users)],
                $categories,
                $specializations[array_rand($specializations)]
            ));
        }

        $manager->flush();
    }

    /**
     * @param BuildCategory[] $categories
     */
    private function generateBuild(
        Generator $faker,
        User $user,
        array $categories,
        Specialization $specialization
    ): Build
    {
        $build = new Build();
        $build->setName($faker->words(2, true))
            ->setAuthor($user)
            ->setSpecialization($specialization)
            ->setLastEditedAt($faker->dateTimeBetween('-1 year'))
            ->setStatus($this->getRandomStatus())
            ->setBenchmark($faker->numberBetween(20000, 50000))
            ->setLink($faker->optional()->url)
            ->setBenchmarkLink($faker->optional()->url);
        $randomKeys = array_rand($categories, 2);
        foreach ($randomKeys as $key) {
            $build->addCategory($categories[$key]);
        }

        return $build;
    }

    private function getRandomStatus(): BuildStatusEnum
    {
        $statuses = BuildStatusEnum::toArray();

        return BuildStatusEnum::from($statuses[array_rand($statuses)]);
    }

    /**
     * @return string[]
     */
    final public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            SpecializationFixtures::class,
            BuildCategoryFixtures::class
        ];
    }
}
