<?php

namespace App\DTO\Entity;

use App\Enum\RolesEnum;

final class UserDTO implements EntityDTOInterface
{
    private string $username;
    private string $password;
    private RolesEnum $role = RolesEnum::GUEST;

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
