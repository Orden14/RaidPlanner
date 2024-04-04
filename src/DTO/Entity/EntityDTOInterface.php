<?php

namespace App\DTO\Entity;

use App\Entity\EntityInterface;

interface EntityDTOInterface
{
    public function setFromObject(EntityInterface $object): self;
}
