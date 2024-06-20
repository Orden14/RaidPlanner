<?php

namespace App\Form;

use App\Entity\Build;
use App\Entity\BuildCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

final class BuildCategoryType extends AbstractType
{
    public function __construct(
        private readonly Packages $packages
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var BuildCategory $buildCategory */
        $buildCategory = $builder->getData();

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom de la catégorie',
                ],
            ])
            ->add('builds', EntityType::class, [
                'class' => Build::class,
                'data' => clone $buildCategory->getBuilds(),
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'selectpicker',
                    'data-style-base' => 'form-control',
                    'data-width' => '100%',
                    'data-live-search' => 'true',
                    'data-live-search-placeholder' => 'Rechercher un build...',
                ],
                'choice_attr' => function ($category) {
                    $name = $category->getName();
                    $iconPath = $this->packages->getUrl('icon/' . $category->getSpecialization()->getIcon());

                    return ['data-content' => "<img
                                    src='{$iconPath}'
                                    class='select-icon'
                                    alt='{$name} icon'
                                    title='{$name}'
                                > {$name}"];
                },
            ])
            ->add('icon', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Icone',
                'constraints' => [
                    new File([
                        'mimeTypes' => ['image/png'],
                        'maxSize' => '2048k',
                        'mimeTypesMessage' => 'Erreur : L\'icone uploadée doit être en format .png',
                        'maxSizeMessage' => 'Erreur : L\'icone uploadée ne doit pas dépasser 2Mo',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BuildCategory::class,
        ]);
    }
}
