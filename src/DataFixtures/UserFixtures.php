<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\RolesEnum;
use App\Service\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FilesystemIterator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserService                 $userService,
        private readonly ParameterBagInterface       $parameterBag,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {}

    final public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $this->purgeProfilePictureDirectory();

        for ($i = 0; $i < 10; $i++) {
            $manager->persist($this->generateUser($faker->userName, RolesEnum::MEMBER));
        }

        $manager->persist($this->generateUser('dev', RolesEnum::DEV));
        $manager->persist($this->generateUser('admin', RolesEnum::ADMIN));
        $manager->persist($this->generateUser('member', RolesEnum::MEMBER));
        $manager->persist($this->generateUser('trial', RolesEnum::TRIAL));
        $manager->persist($this->generateUser('old_member', RolesEnum::OLD_MEMBER));
        $manager->persist($this->generateUser('guest', RolesEnum::GUEST));

        $manager->flush();
    }


    private function generateUser(string $username, RolesEnum $role): User
    {
        $user = new User();
        $user->setUsername($username)
            ->setRole($role)
            ->setPassword($this->userPasswordHasher->hashPassword(
                $user,
                $username
            ));
        $this->userService->setDefaultProfilePicture($user);

        return $user;
    }

    private function purgeProfilePictureDirectory(): void
    {
        $files = new FilesystemIterator($this->parameterBag->get('profile_picture_directory'));

        foreach ($files as $file) {
            if ($file->isFile() && $file->getFilename() !== '.gitignore') {
                unlink($file->getPathname());
            }
        }
    }
}
