<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\Specialization;
use App\Repository\JobRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

final class SpecializationType extends AbstractType
{
    public function __construct(
        private readonly Packages      $packages,
        private readonly JobRepository $jobRepository
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom de la spécialisation'
                ]
            ])
            ->add('job', EntityType::class, [
                'label' => 'Classe',
                'class' => Job::class,
                'choices' => $this->jobRepository->findAllWithoutDefault(),
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'selectpicker',
                    'data-style-base' => 'form-control',
                    'data-width' => '100%',
                    'data-live-search' => 'true',
                    'data-live-search-placeholder' => 'Rechercher une classe...'
                ],
                'choice_attr' => function ($job) {
                    $name = $job->getName();
                    $iconPath = $this->packages->getUrl('icon/' . $job->getIcon());
                    return ['data-content' => "<img
                        src='$iconPath'
                        class='select-icon'
                        alt='$name icon'
                        title='$name'
                    > $name"];
                }
            ])
            ->add('icon', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Icone',
                'constraints' => [
                    new File([
                        'mimeTypes' => ['image/png'],
                        'maxSize' => '2048k',
                        'mimeTypesMessage' => 'Erreur : L\'icone uploadée doit être en format .png',
                        'maxSizeMessage' => 'Erreur : L\'icone uploadée ne doit pas dépasser 2Mo'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Specialization::class,
        ]);
    }
}
