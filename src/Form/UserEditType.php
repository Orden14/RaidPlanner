<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\RolesEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $user */
        $user = $options['data'];

        $builder
            ->add('username', TextType::class, [
                'label' => "Nom d'utilisateur"
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    RolesEnum::getRoleDisplayName(RolesEnum::DEV) => RolesEnum::DEV,
                    RolesEnum::getRoleDisplayName(RolesEnum::ADMIN) => RolesEnum::ADMIN,
                    RolesEnum::getRoleDisplayName(RolesEnum::MEMBER) => RolesEnum::MEMBER,
                    RolesEnum::getRoleDisplayName(RolesEnum::TRIAL) => RolesEnum::TRIAL,
                    RolesEnum::getRoleDisplayName(RolesEnum::OLD_MEMBER) => RolesEnum::OLD_MEMBER,
                    RolesEnum::getRoleDisplayName(RolesEnum::GUEST) => RolesEnum::GUEST,
                ],
                'data' => $user->getRole(),
                'label' => 'Rôle',
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
