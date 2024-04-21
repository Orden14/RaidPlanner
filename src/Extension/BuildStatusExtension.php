<?php

namespace App\Extension;

use App\Enum\BuildStatusEnum;
use InvalidArgumentException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BuildStatusExtension extends AbstractExtension
{
    final public function getFunctions(): array
    {
        return [
            new TwigFunction('get_status_style_class_name', [$this, 'getStatusStyleClassName']),
        ];
    }

    /**
     * @throws InvalidArgumentException
     */
    final public function getStatusStyleClassName(string $status): string
    {
        return BuildStatusEnum::getStatusStyleClassName($status);
    }
}
