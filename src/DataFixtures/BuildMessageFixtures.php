<?php

namespace App\DataFixtures;

use App\Entity\BuildMessage;
use App\Repository\BuildRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Random\RandomException;

class BuildMessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly BuildRepository $buildRepository
    ) {}

    /**
     * @throws RandomException
     */
    final public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = $this->userRepository->findAll();
        $builds = $this->buildRepository->findAll();

        foreach ($builds as $build) {
            $numMessages = random_int(0, 5);

            $date = new DateTime();

            for ($i = 0; $i < $numMessages; $i++) {
                $message = new BuildMessage();
                $message->setAuthor($users[array_rand($users)])
                    ->setBuild($build)
                    ->setContent($faker->sentence(10))
                    ->setPostedAt($date);

                $manager->persist($message);
            }
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    final public function getDependencies(): array
    {
        return [
            BuildFixtures::class
        ];
    }
}
