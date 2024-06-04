<?php

namespace App\Service\Build;

use App\Entity\Build;
use Symfony\Component\Asset\Packages;

final readonly class BuildDisplayService
{
    public function __construct(
        private Packages $packages,
    ) {}

    /**
     * @return array<string, string>
     */
    public function getBuildSelectDisplay(Build $build): array
    {
        $iconPath = $this->packages->getUrl('icon/' . $build->getSpecialization()?->getIcon());

        $name = $build->getName();
        $profilePictureHtml = "<img src='$iconPath' class='select-icon' alt='$name icon' title='$name'>";

        return ["data-content" => "$profilePictureHtml $name {$this->getCategoryIconsHtml($build)}"];
    }

    private function getCategoryIconsHtml(Build $build): string
    {
        $icons = '';

        foreach ($build->getCategories() as $category) {
            $iconPath = $this->packages->getUrl('icon/' . $category->getIcon());
            $icons .= "<img src='$iconPath' alt='{$category->getName()} icon' title='{$category->getName()}' class='select-icon'>";
        }

        return $icons;
    }
}
