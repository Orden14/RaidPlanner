<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\RolesEnum;
use App\Service\User\UserProfileService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FilesystemIterator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly KernelInterface             $kernel,
        private readonly ParameterBagInterface       $parameterBag,
        private readonly UserProfileService          $userProfileService,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {}

    final public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        if ($this->kernel->getEnvironment() === 'dev') {
            $this->purgeProfilePictureDirectory();
        }

        for ($i = 0; $i < 10; $i++) {
            $manager->persist($this->generateUser($faker->userName, RolesEnum::MEMBER));
        }

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

        if ($this->kernel->getEnvironment() !== 'dev') {
            $user->setProfilePicture('emptyFileForTest.png');
        } else {
            $this->userProfileService->setDefaultProfilePicture($user);
        }

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
