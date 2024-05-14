<?php

namespace App\Form\GuildEvent;

use App\Entity\GuildEvent;
use App\Enum\InstanceTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class GuildEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEventNew = $options['data']->getId() === null;

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('start', null, [
                'widget' => 'single_text',
                'label' => 'Date de début',
            ])
            ->add('end', null, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
            ])
            ->add('color', ColorType::class, [
                'label' => 'Couleur',
            ])
            ->add('oldMembersAllowed', ChoiceType::class, [
                'label' => 'Anciens membres',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
            ])
            ->add('membersManageEvent', ChoiceType::class, [
                'label' => 'Gestion par les membres',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
            ]);

        if ($isEventNew) {
            $builder->add('type', EnumType::class, [
                'class' => InstanceTypeEnum::class,
                'label' => 'Type d\'évènement',
                'choice_label' => fn(InstanceTypeEnum $enum) => $enum->value,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GuildEvent::class,
        ]);
    }
}
