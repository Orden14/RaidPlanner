<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Enum\RolesEnum;
use App\Factory\UserFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserFactory $userFactory,
    )
    {}

    final public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 30; $i++) {
            $username = $faker->userName();

            $user = $this->userFactory->createUser(
                $username,
                $username
            );

            $manager->persist($user);
        }

        $manager->persist($this->generateAdmin());
        $manager->persist($this->generateMember());
        $manager->persist($this->generateGuest());

        $manager->flush();
    }


    private function generateAdmin(): User
    {
        return $this->userFactory->createUser(
            'admin',
            'admin',
            RolesEnum::ADMIN
        );
    }

    private function generateMember(): User
    {
        return $this->userFactory->createUser(
            'member',
            'member',
            RolesEnum::MEMBER
        );
    }

    private function generateGuest(): User
    {
        return $this->userFactory->createUser(
            'guest',
            'guest',
            RolesEnum::GUEST
        );
    }
}
