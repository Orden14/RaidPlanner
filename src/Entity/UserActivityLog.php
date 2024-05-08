<?php

namespace App\Entity;

use App\Enum\UserActivityLogTypeEnum;
use App\Repository\UserActivityLogRepository;
use DateTime;
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
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $logMessage = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $date;

    public function __construct()
    {
        $this->date = new DateTime();
    }

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getType(): ?UserActivityLogTypeEnum
    {
        return UserActivityLogTypeEnum::getLogTypeFromValue($this->type);
    }

    final public function setType(UserActivityLogTypeEnum $type): self
    {
        $this->type = $type->value;

        return $this;
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
