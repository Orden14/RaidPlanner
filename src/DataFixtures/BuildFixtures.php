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
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class BuildFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepository           $userRepository,
        private readonly BuildCategoryRepository  $buildCategoryRepository,
        private readonly SpecializationRepository $specializationRepository
    ) {}

    final public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = $this->userRepository->findAll();
        $categories = $this->buildCategoryRepository->findAll();
        $specializations = array_filter($this->specializationRepository->findAll(), static function($specialization) {
            return !$specialization->getJob()?->isDefault();
        });

        for ($i = 0; $i < 40; $i++) {
            $manager->persist($this->generateBuild(
                $faker,
                $users[array_rand($users)],
                $this->getStatus($i),
                $categories,
                $specializations[array_rand($specializations)]
            ));
        }

        $this->generateDefaultBuilds($manager);

        $manager->flush();
    }

    /**
     * @param BuildCategory[] $categories
     */
    private function generateBuild(
        Generator       $faker,
        User            $user,
        BuildStatusEnum $status,
        array           $categories,
        Specialization  $specialization
    ): Build
    {
        $build = new Build();
        $build->setName($faker->words(2, true))
            ->setAuthor($user)
            ->setSpecialization($specialization)
            ->setLastEditedAt($faker->dateTimeBetween('-1 year'))
            ->setStatus($status)
            ->setBenchmark($faker->numberBetween(20000, 50000))
            ->setLink($faker->optional()->url)
            ->setBenchmarkLink($faker->optional()->url)
            ->setVideoLink($faker->optional()->url);
        $randomKeys = array_rand($categories, 2);
        foreach ($randomKeys as $key) {
            $build->addCategory($categories[$key]);
        }

        return $build;
    }

    private function getStatus(int $i): BuildStatusEnum
    {
        if ($i < 25) {
            $status = BuildStatusEnum::META;
        } elseif ($i < 35) {
            $status = BuildStatusEnum::NOT_META;
        } else {
            $status = BuildStatusEnum::OUTDATED;
        }

        return $status;
    }

    private function generateDefaultBuilds(ObjectManager $manager): void
    {
        $admin = $this->userRepository->findOneBy(['username' => 'admin']);
        $defaultSpecialization = $this->specializationRepository->findOneBy(['name' => 'Default']);

        foreach ($this->getDefaultBuildsData() as $data) {
            $build = new Build();
            $build->setName($data['name'])
                ->setAuthor($admin)
                ->setSpecialization($defaultSpecialization)
                ->setLastEditedAt(new DateTime())
                ->setStatus(BuildStatusEnum::META)
                ->setBenchmark(0);
            foreach ($data['categories'] as $category) {
                $build->addCategory($category);
            }

            $manager->persist($build);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function getDefaultBuildsData(): array
    {
        $pdps = $this->buildCategoryRepository->findOneBy(['name' => 'Pdps']);
        $cdps = $this->buildCategoryRepository->findOneBy(['name' => 'Cdps']);
        $heal = $this->buildCategoryRepository->findOneBy(['name' => 'Heal']);
        $quickness = $this->buildCategoryRepository->findOneBy(['name' => 'Quickness']);
        $alacrity = $this->buildCategoryRepository->findOneBy(['name' => 'Alacrity']);

        return [
            [
                'name' => 'Pdps bis',
                'categories' => [$pdps]
            ],
            [
                'name' => 'Cdps bis',
                'categories' => [$cdps]
            ],
            [
                'name' => 'Heal alac',
                'categories' => [$heal, $alacrity]
            ],
            [
                'name' => 'Heal quick',
                'categories' => [$heal, $quickness]
            ],
            [
                'name' => 'Power alac',
                'categories' => [$pdps, $alacrity]
            ],
            [
                'name' => 'Condi quick',
                'categories' => [$cdps, $quickness]
            ]
        ];
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
