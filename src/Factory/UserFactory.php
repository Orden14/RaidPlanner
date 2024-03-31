<?php

namespace App\Factory;

use App\Entity\User;
use App\Enum\RolesEnum;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as PasswordHasher;

readonly class UserFactory
{
    public function __construct(
        private PasswordHasher $passwordHasher,
    ) {}

    final public function createUser(
        string $username,
        string $password,
        RolesEnum $role = RolesEnum::MEMBER,
    ): User
    {
        $user = new User();

        $user->setUsername($username)
            ->setPassword($this->passwordHasher->hashPassword($user, $password))
            ->setRole($role)
        ;

        return $user;
    }
}
