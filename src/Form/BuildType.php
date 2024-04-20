<?php

namespace App\Form;

use App\Entity\Build;
use App\Entity\BuildCategory;
use App\Entity\Specialization;
use App\Repository\SpecializationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BuildType extends AbstractType
{
    public function __construct(
        private readonly Packages $packages,
        private readonly SpecializationRepository $specializationRepository
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('link', TextType::class, [
                'label' => 'Lien externe',
                'required' => false
            ])
            ->add('meta', ChoiceType::class, [
                'label' => 'Meta',
                'choices' => [
                    'Build meta' => true,
                    'Build hors meta' => false,
                ],
            ])
            ->add('specialization', EntityType::class, [
                'class' => Specialization::class,
                'choices' => $this->specializationRepository->findAllOrderedByJob(false),
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'selectpicker',
                    'data-style-base' => 'form-control',
                    'data-width' => '100%',
                    'data-live-search' => 'true',
                    'data-live-search-placeholder' => 'Rechercher une spécialisation...'
                ],
                'choice_attr' => function($specialization) {
                    $name = $specialization->getName();
                    $iconPath = $this->packages->getUrl('icon/' . $specialization->getIcon());
                    return ['data-content' => "<img
                        src='$iconPath'
                        class='select-icon'
                        alt='$name icon'
                        title='$name'
                    /> $name"];
                }
            ])
            ->add('categories', EntityType::class, [
                'class' => BuildCategory::class,
                'choice_label' => 'name',
                'multiple' => true,
                'attr' => [
                    'class' => 'selectpicker',
                    'data-style-base' => 'form-control',
                    'data-width' => '100%',
                    'data-live-search' => 'true',
                    'data-live-search-placeholder' => 'Rechercher une spécialisation...'
                ],
                'choice_attr' => function($category) {
                    $name = $category->getName();
                    $iconPath = $this->packages->getUrl('icon/' . $category->getIcon());
                    return ['data-content' => "<img
                        src='$iconPath'
                        class='select-icon'
                        alt='$name icon'
                        title='$name'
                    /> $name"];
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Build::class,
        ]);
    }
}
