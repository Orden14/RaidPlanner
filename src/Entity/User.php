<?php

namespace App\Entity;

use App\Enum\RolesEnum;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var string[] The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getUsername(): ?string
    {
        return $this->username;
    }

    final public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    final public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @deprecated use singular method getRole() instead
     *
     * @see UserInterface
     *
     * @return array<int, string>
     */
    final public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @deprecated use singular method setRole() instead
     *
     * @see UserInterface
     *
     * @param array<int, string> $roles
     */
    final public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    final public function getRole(): string
    {
        return $this->roles[0];
    }

    final public function setRole(RolesEnum $role): self
    {
        $this->roles = [$role->value];

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    final public function getPassword(): string
    {
        return $this->password;
    }

    final public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    final public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
