<?php

namespace App\Form\GuildEvent;

use App\Entity\Build;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Repository\BuildRepository;
use App\Service\BuildDisplayService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PlayerSlotType extends AbstractType
{
    public function __construct(
        private readonly BuildRepository     $buildRepository,
        private readonly BuildDisplayService $buildDisplayService,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ?PlayerSlot $playerSlot */
        $playerSlot = $options['data'];

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
                'placeholder' => 'Aucun build',
                'data' => $playerSlot?->getBuild(),
                'required' => false,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'selectpicker',
                    'data-style-base' => 'form-control',
                    'data-width' => '100%',
                    'data-live-search' => 'true',
                    'data-live-search-placeholder' => 'Rechercher un build...',
                    'data-container' => 'body',
                ],
                'choice_attr' => function ($build) {
                    return $this->buildDisplayService->getBuildSelectDisplay($build);
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
