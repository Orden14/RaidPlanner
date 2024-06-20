<?php

namespace App\Form\Security;

use App\Util\Security\PasswordConstraintUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

final class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'mapped' => false,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'mapped' => false,
                'first_options' => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmez le mot de passe'],
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => PasswordConstraintUtil::getPasswordConstraints(),
            ])
        ;
    }
}
