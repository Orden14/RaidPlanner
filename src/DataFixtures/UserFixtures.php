<?php

namespace App\DataFixtures;

use App\DTO\Entity\UserDTO;
use App\Entity\User;
use App\Enum\RolesEnum;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserFactory $userFactory,
    ) {}

    final public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 30; $i++) {
            $username = $faker->userName();

            $userDTO = (new UserDTO())
                ->setUsername($username)
                ->setPassword($username)
                ->setRole(RolesEnum::MEMBER)
            ;

            $manager->persist($this->userFactory->create($userDTO));
        }

        $manager->persist($this->generateAdmin());
        $manager->persist($this->generateMember());
        $manager->persist($this->generateGuest());

        $manager->flush();
    }


    private function generateAdmin(): User
    {
        $adminDTO = (new UserDTO())
            ->setUsername('admin')
            ->setPassword('admin')
            ->setRole(RolesEnum::ADMIN)
        ;

        return $this->userFactory->create($adminDTO);
    }

    private function generateMember(): User
    {
        $memberDTO = (new UserDTO())
            ->setUsername('member')
            ->setPassword('member')
            ->setRole(RolesEnum::MEMBER)
        ;

        return $this->userFactory->create($memberDTO);
    }

    private function generateGuest(): User
    {
        $guestDTO = (new UserDTO())
            ->setUsername('guest')
            ->setPassword('guest')
            ->setRole(RolesEnum::GUEST)
        ;

        return $this->userFactory->create($guestDTO);
    }
}
