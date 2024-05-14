<?php

namespace App\Form;

use App\Entity\Build;
use App\Entity\BuildCategory;
use App\Entity\Specialization;
use App\Enum\BuildStatusEnum;
use App\Repository\SpecializationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BuildType extends AbstractType
{
    public function __construct(
        private readonly Packages                 $packages,
        private readonly SpecializationRepository $specializationRepository
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('link', TextType::class, [
                'label' => 'Lien build',
                'required' => false
            ])
            ->add('videoLink', TextType::class, [
                'label' => 'Lien vidéo',
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Meta' => BuildStatusEnum::META,
                    'Hors meta' => BuildStatusEnum::NOT_META,
                    'Outdated' => BuildStatusEnum::OUTDATED,
                ],
                'attr' => [
                    'class' => 'selectpicker',
                    'data-style-base' => 'form-control',
                    'data-width' => '100%',
                ],
                'choice_attr' => function ($status) {
                    $styleClass = BuildStatusEnum::getStatusStyleClassName($status->value) . ' ' . 'align-middle';
                    return ['data-content' => "<span class='$styleClass'></span> " . $status->value];
                }
            ])
            ->add('specialization', EntityType::class, [
                'label' => 'Spécialisation',
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
                'choice_attr' => function ($specialization) {
                    $name = $specialization->getName();
                    $iconPath = $this->packages->getUrl('icon/' . $specialization->getIcon());
                    return ['data-content' => "<img
                        src='$iconPath'
                        class='select-icon'
                        alt='$name icon'
                        title='$name'
                    > $name"];
                }
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Catégories',
                'class' => BuildCategory::class,
                'choice_label' => 'name',
                'multiple' => true,
                'attr' => [
                    'class' => 'selectpicker',
                    'data-style-base' => 'form-control',
                    'data-width' => '100%',
                ],
                'choice_attr' => function ($category) {
                    $name = $category->getName();
                    $iconPath = $this->packages->getUrl('icon/' . $category->getIcon());
                    return ['data-content' => "<img
                        src='$iconPath'
                        class='select-icon'
                        alt='$name icon'
                        title='$name'
                    > $name"];
                }
            ])
            ->add('benchmark', IntegerType::class, [
                'label' => 'Benchmark',
                'required' => false
            ])
            ->add('benchmarkLink', TextType::class, [
                'label' => 'Log du benchmark',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Build::class,
        ]);
    }
}
