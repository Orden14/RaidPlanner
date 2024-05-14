<?php

namespace App\Form\GuildEvent;

use App\Entity\Build;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Repository\BuildRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PlayerSlotType extends AbstractType
{
    public function __construct(
        private readonly Packages        $packages,
        private readonly BuildRepository $buildRepository,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tank', CheckboxType::class, [
                'label' => 'Tank',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
            ->add('build', EntityType::class, [
                'label' => false,
                'class' => Build::class,
                'choices' => $this->buildRepository->findMetaBuilds(),
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'selectpicker',
                    'data-style-base' => 'form-control',
                    'data-width' => '100%',
                    'data-live-search' => 'true',
                    'data-live-search-placeholder' => 'Rechercher un build...'
                ],
                'choice_attr' => function ($build) {
                    $name = $build->getName();
                    $iconPath = $this->packages->getUrl('icon/' . $build->getSpecialization()->getIcon());
                    return ['data-content' => "<img
                            src='$iconPath'
                            class='select-icon'
                            alt='$name icon'
                            title='$name'
                        > $name"];
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlayerSlot::class,
        ]);
    }
}
