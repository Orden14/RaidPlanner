<?php

namespace App\Form;

use App\Entity\Encounter;
use App\Entity\Instance;
use App\Repository\InstanceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EncounterType extends AbstractType
{
    public function __construct(
        private readonly InstanceRepository $instanceRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('instance', EntityType::class, [
                'label' => 'Instance',
                'class' => Instance::class,
                'choices' => $this->instanceRepository->findAll(),
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'selectpicker',
                    'data-style-base' => 'form-control',
                    'data-width' => '100%',
                    'data-live-search' => 'true',
                    'data-live-search-placeholder' => 'Rechercher une instance...',
                ],
                'choice_attr' => static fn ($instance) => ['data-content' => $instance->getName()],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Encounter::class,
        ]);
    }
}
