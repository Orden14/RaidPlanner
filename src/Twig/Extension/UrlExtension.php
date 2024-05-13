<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UrlExtension extends AbstractExtension
{
    /**
     * @return TwigFilter[]
     */
    final public function getFilters(): array
    {
        return [
            new TwigFilter('auto_link', $this->autoLink(...)),
            new TwigFilter('shorten_url', $this->shortenUrl(...))
        ];
    }

    final public function autoLink(string $text): string
    {
        return preg_replace('/((http|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?)/', '<a href="$1">$1</a>', $text);
    }

    final public function shortenUrl(string $url): string
    {
        $parsedUrl = parse_url($url);

        return $parsedUrl['host'] ?? '';
    }
}
