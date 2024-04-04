<?php

namespace App\Factory;

use App\DTO\Entity\EntityDTOInterface;

interface FactoryInterface
{
    public function create(EntityDTOInterface $dto): object;
}
