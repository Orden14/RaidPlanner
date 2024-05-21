<?php

namespace App\Entity;

use App\Entity\Trait\UserGuildEventPropertiesTrait;
use App\Enum\RolesEnum;
use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: "Un compte possède déjà ce nom d'utilisateur.")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UserGuildEventPropertiesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull(message: 'Le nom d\'utilisateur ne peut pas être vide.')]
    #[ORM\Column(length: 180)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $profilePicture = null;

    /**
     * @var string[] The user roles
     */
    #[Assert\Choice(
        choices: [
            RolesEnum::ADMIN->value,
            RolesEnum::MEMBER->value,
            RolesEnum::TRIAL->value,
            RolesEnum::OLD_MEMBER->value,
            RolesEnum::GUEST->value
        ],
        multiple: true,
        max: 1,
        maxMessage: "Un utilisateur doit avoir un seul rôle."
    )]
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $joinedAt;

    public function __construct()
    {
        $this->playerSlots = new ArrayCollection();
        $this->eventAttendances = new ArrayCollection();
        $this->joinedAt = new DateTime();
    }

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
        return (string)$this->username;
    }

    final public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    final public function setProfilePicture(string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * @return array<int, string>
     * @see UserInterface
     *
     * @deprecated use singular method getRole() instead
     *
     */
    final public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array<int, string> $roles
     * @see UserInterface
     *
     * @deprecated use singular method setRole() instead
     *
     */
    final public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    final public function getRole(): RolesEnum
    {
        return RolesEnum::from(reset($this->roles));
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
     *
     */
    final public function eraseCredentials(): void {}

    final public function getJoinedAt(): DateTimeInterface
    {
        return $this->joinedAt;
    }

    final public function setJoinedAt(DateTimeInterface $joinedAt): self
    {
        $this->joinedAt = $joinedAt;

        return $this;
    }
}
