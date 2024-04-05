<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\RolesEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {}

    final public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 30; $i++) {
            $manager->persist($this->generateUser($faker->userName, RolesEnum::MEMBER));
        }

        $manager->persist($this->generateUser('admin', RolesEnum::ADMIN));
        $manager->persist($this->generateUser('member', RolesEnum::MEMBER));
        $manager->persist($this->generateUser('old_member', RolesEnum::OLD_MEMBER));
        $manager->persist($this->generateUser('guest', RolesEnum::GUEST));

        $manager->flush();
    }


    private function generateUser(string $username, RolesEnum $role): User
    {
        $user = new User();
        return $user->setUsername($username)
            ->setRole($role)
            ->setPassword($this->userPasswordHasher->hashPassword(
                $user,
                $username
            ));
    }
}
