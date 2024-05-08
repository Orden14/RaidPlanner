<?php

namespace App\Entity;

use App\Repository\UserActivityLogRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserActivityLogRepository::class)]
class UserActivityLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $logMessage = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $date = null;

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getLogMessage(): ?string
    {
        return $this->logMessage;
    }

    final public function setLogMessage(string $logMessage): self
    {
        $this->logMessage = $logMessage;

        return $this;
    }

    final public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    final public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
