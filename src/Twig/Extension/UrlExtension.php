<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AutoLinkExtension extends AbstractExtension
{
    final public function getFilters(): array
    {
        return [
            new TwigFilter('auto_link', [$this, 'autoLink']),
        ];
    }

    final public function autoLink(string $text): string
    {
        return preg_replace('/((http|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?)/', '<a href="$1">$1</a>', $text);
    }
}
