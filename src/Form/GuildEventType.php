<?php

namespace App\Form;

use App\Entity\GuildEvent;
use App\Enum\GuildEventTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('type', EnumType::class, [
                'class' => GuildEventTypeEnum::class,
                'label' => 'Type d\'évènement',
                'choice_label' => fn (GuildEventTypeEnum $enum) => $enum->value,
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
                'label' => 'Les anciens membres sont-ils autorisés ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GuildEvent::class,
        ]);
    }
}
