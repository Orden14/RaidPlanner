<?php

namespace App\Form;

use App\Entity\GuildEvent;
use App\Enum\GuildEventTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class GuildEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('type', EnumType::class, [
                'class' => GuildEventTypeEnum::class,
                'choice_label' => fn (GuildEventTypeEnum $enum) => $enum->value,
            ])
            ->add('start', null, [
                'widget' => 'single_text',
            ])
            ->add('end', null, [
                'widget' => 'single_text',
            ])
            ->add('color', ColorType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GuildEvent::class,
        ]);
    }
}
