<?php

namespace App\Entity;

use App\Repository\RegistrationTokenRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Random\RandomException;

#[ORM\Entity(repositoryClass: RegistrationTokenRepository::class)]
class RegistrationToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $token;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $expiryDate;

    #[ORM\Column]
    private int $uses;

    #[ORM\Column]
    private int $used;

    /**
     * @throws RandomException
     */
    public function __construct()
    {
        $this->token = bin2hex(random_bytes(10));
        $this->uses = 1;
        $this->used = 0;
    }

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getToken(): string
    {
        return $this->token;
    }

    final public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    final public function getExpiryDate(): DateTimeInterface
    {
        return $this->expiryDate;
    }

    final public function setExpiryDate(DateTimeInterface $expiryDate): self
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    final public function getUses(): int
    {
        return $this->uses;
    }

    final public function setUses(int $uses): self
    {
        $this->uses = $uses;

        return $this;
    }

    final public function getUsed(): int
    {
        return $this->used;
    }

    final public function setUsed(int $used): self
    {
        $this->used = $used;

        return $this;
    }
}
