<?php

namespace App\Factory;

use App\DTO\Entity\EntityDTOInterface;
use App\DTO\Entity\UserDTO;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as PasswordHasher;

final readonly class UserFactory implements FactoryInterface
{
    public function __construct(
        private PasswordHasher $passwordHasher,
    ) {}

    /**
     * @param UserDTO $dto
     */
    public function create(EntityDTOInterface $dto): User
    {
        $user = new User();

        $user->setUsername($dto->getUsername())
            ->setPassword($this->passwordHasher->hashPassword($user, $dto->getPassword()))
            ->setRole($dto->getRole())
        ;

        return $user;
    }
}
