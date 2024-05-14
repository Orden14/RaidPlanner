<?php

namespace App\Form\GuildEvent;

use App\Entity\Encounter;
use App\Entity\GuildEventRelation\EventEncounter;
use App\Enum\GuildEventTypeEnum;
use App\Repository\EncounterRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EventEncounterType extends AbstractType
{
    public function __construct(
        private readonly EncounterRepository $encounterRepository,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var EventEncounter $eventEncounter */
        $eventEncounter = $options['data'];

        $builder->add('encounter', EntityType::class, [
            'label' => 'Combat',
            'class' => Encounter::class,
            'choices' => $this->encounterRepository->findAll(),
            'choice_label' => 'name',
            'attr' => [
                'class' => 'selectpicker',
                'data-style-base' => 'form-control',
                'data-width' => '100%',
                'data-live-search' => 'true',
                'data-live-search-placeholder' => 'Rechercher un combat...'
            ],
            'choice_attr' => function ($encounter) {
                return ['data-content' => "{$encounter->getInstance()->getTag()} - {$encounter->getName()}"];
            }
        ]);

        for ($i = 0; $i < GuildEventTypeEnum::getMaxPlayersByType($eventEncounter->getGuildEvent()?->getType()); $i++) {
            $builder->add("playerSlot$i", PlayerSlotType::class, [
                'label' => false,
                'mapped' => false,
                'data' => $eventEncounter->getPlayerSlots()[$i] ?? null,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventEncounter::class,
        ]);
    }
}
