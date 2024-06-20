<?php

namespace App\Form\Security;

use App\Entity\RegistrationToken;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RegistrationTokenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('expiryTime', ChoiceType::class, [
                'choices' => [
                    '5 minutes' => 300,
                    '15 minutes' => 900,
                    '1 heure' => 3600,
                    '1 jour' => 86400,
                    '1 semaine' => 604800,
                ],
                'required' => true,
                'data' => 900,
                'label' => 'Temps avant expiration',
                'mapped' => false,
            ])
            ->add('uses', ChoiceType::class, [
                'choices' => [
                    'Illimité' => -1,
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                    10 => 10,
                ],
                'required' => true,
                'data' => 1,
                'label' => "Nombre d'utilisations",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegistrationToken::class,
        ]);
    }
}
