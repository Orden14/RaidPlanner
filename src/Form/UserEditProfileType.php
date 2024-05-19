<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

final class UserEditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom de compte'
            ])
            ->add('profilePicture', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Image de profil',
                'constraints' => [
                    new File([
                        'mimeTypes' => ['image/png', 'image/jpeg'],
                        'maxSize' => '5120k',
                        'mimeTypesMessage' => 'Erreur : L\'image uploadée doit être en format .png ou .jpg',
                        'maxSizeMessage' => 'Erreur : L\'image uploadée ne doit pas dépasser 5Mo'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
