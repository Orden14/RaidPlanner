<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\Specialization;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecializationType extends AbstractType
{
    final public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('job', EntityType::class, [
                'class' => Job::class,
                'choice_label' => 'name'
            ])
            ->add('icon', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Icone',
            ])
        ;
    }

    final public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Specialization::class,
        ]);
    }
}
