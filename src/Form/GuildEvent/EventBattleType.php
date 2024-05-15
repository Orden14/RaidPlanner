<?php

namespace App\Form\GuildEvent;

use App\Entity\Encounter;
use App\Entity\GuildEventRelation\EventBattle;
use App\Enum\InstanceTypeEnum;
use App\Repository\EncounterRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EventBattleType extends AbstractType
{
    public function __construct(
        private readonly EncounterRepository $encounterRepository,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var EventBattle $eventBattle */
        $eventBattle = $options['data'];

        $builder->add('encounter', EntityType::class, [
            'label' => 'Combat',
            'class' => Encounter::class,
            'choices' => $this->encounterRepository->findAllByType($eventBattle->getGuildEvent()?->getType()),
            'choice_label' => 'name',
            'attr' => [
                'class' => 'selectpicker',
                'data-style-base' => 'form-control',
                'data-width' => '100%',
                'data-live-search' => 'true',
                'data-live-search-placeholder' => 'Rechercher un combat...'
            ],
            'choice_attr' => function ($encounter) use ($eventBattle) {
                $prefix = $eventBattle->getGuildEvent()?->getType() === InstanceTypeEnum::RAID ? "{$encounter->getInstance()->getTag()} -" : '';
                return ['data-content' => "$prefix {$encounter->getName()}"];
            }
        ]);

        for ($i = 0; $i < InstanceTypeEnum::getMaxPlayersByType($eventBattle->getGuildEvent()?->getType()); $i++) {
            $builder->add("playerSlot$i", PlayerSlotType::class, [
                'label' => false,
                'mapped' => false,
                'data' => $eventBattle->getPlayerSlots()[$i] ?? null,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventBattle::class,
        ]);
    }
}
