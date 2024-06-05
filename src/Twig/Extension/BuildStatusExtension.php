<?php

namespace App\Twig\Extension;

use App\Enum\BuildStatusEnum;
use InvalidArgumentException;
use Override;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class BuildStatusExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    #[Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_status_style_class_name', $this->getStatusStyleClassName(...)),
        ];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getStatusStyleClassName(string $status): string
    {
        return BuildStatusEnum::getStatusStyleClassName($status);
    }
}
