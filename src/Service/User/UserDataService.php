<?php

namespace App\Service\User;

use App\Entity\User;
use App\Enum\RolesEnum;
use App\Repository\UserRepository;

final readonly class UserDataService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @return User[]
     */
    public function getActiveMembers(): array
    {
        $users = $this->userRepository->findAllOrderedByName();

        return array_filter($users, static fn (User $user) => in_array($user->getRole(), RolesEnum::getActiveGuildRoles(), true));
    }
}
