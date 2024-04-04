<?php

namespace App\DTO\Entity;

use App\Entity\EntityInterface;
use App\Entity\User;
use App\Enum\RolesEnum;

final class UserDTO implements EntityDTOInterface
{
    private string $username;
    private string $password;
    private RolesEnum $role = RolesEnum::GUEST;

    /**
     * @param User $object
     */
    public function setFromObject(EntityInterface $object):self
    {
        $this->username = $object->getUsername();
        $this->password = $object->getPassword();
        $this->role = RolesEnum::getRoleFromValue($object->getRole());

        return $this;
    }
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setRole(RolesEnum $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRole(): RolesEnum
    {
        return $this->role;
    }
}
