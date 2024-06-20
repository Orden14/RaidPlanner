<?php

namespace App\Util\Form;

use App\Entity\BuildCategory;
use Symfony\Component\Asset\Packages;

final readonly class BuildFormFieldHelper
{
    public function __construct(
        private Packages $packages
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getCategoiesFieldOptions(): array
    {
        return [
            'label' => 'Catégories',
            'class' => BuildCategory::class,
            'choice_label' => 'name',
            'multiple' => true,
            'attr' => [
                'class' => 'selectpicker',
                'data-style-base' => 'form-control',
                'data-width' => '100%',
            ],
            'choice_attr' => function ($category) {
                $name = $category->getName();
                $iconPath = $this->packages->getUrl('icon/' . $category->getIcon());

                return ['data-content' => "<img
                        src='{$iconPath}'
                        class='select-icon'
                        alt='{$name} icon'
                        title='{$name}'
                    > {$name}"];
            },
        ];
    }
}
