<?php

namespace App\Entity;

use App\Repository\BuildMessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuildMessageRepository::class)]
class BuildMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\ManyToOne(inversedBy: 'buildMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Build $build = null;

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getMessage(): ?string
    {
        return $this->message;
    }

    final public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    final public function getBuild(): ?Build
    {
        return $this->build;
    }

    final public function setBuild(?Build $build): static
    {
        $this->build = $build;

        return $this;
    }
}
