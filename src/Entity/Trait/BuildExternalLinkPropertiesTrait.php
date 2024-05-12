<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait BuildExternalLinkPropertiesTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $benchmarkLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $videoLink = null;

    final public function getLink(): ?string
    {
        return $this->link;
    }

    final public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    final public function getBenchmarkLink(): ?string
    {
        return $this->benchmarkLink;
    }

    final public function setBenchmarkLink(?string $benchmarkLink): self
    {
        $this->benchmarkLink = $benchmarkLink;

        return $this;
    }

    final public function getVideoLink(): ?string
    {
        return $this->videoLink;
    }

    final public function setVideoLink(?string $videoLink): self
    {
        $this->videoLink = $videoLink;

        return $this;
    }
}