<?php

namespace App\Form\GuildEvent;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class AttendBackupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('comment', TextType::class, [
            'label' => 'Ajouter un commentaire ?',
            'required' => false,
            'attr' => [
                'placeholder' => 'Commentaire',
                'class' => 'form-control',
            ],
        ]);
    }
}
