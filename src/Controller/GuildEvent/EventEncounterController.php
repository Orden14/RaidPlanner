<?php

namespace App\Controller\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventEncounter;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Enum\GuildEventTypeEnum;
use App\Enum\RolesEnum;
use App\Form\GuildEvent\EventEncounterType;
use App\Util\Form\FormFlashHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::MEMBER->value)]
#[Route('/event-encounter', name: 'guild_event_encounter_')]
class EventEncounterController extends AbstractController
{
    public function __construct(
        private readonly FormFlashHelper        $formFlashHelper,
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/add/{guildEvent}', name: 'add', methods: ['GET', 'POST'])]
    final public function addToEvent(Request $request, GuildEvent $guildEvent): Response
    {
        $eventEncounter = (new EventEncounter())->setGuildEvent($guildEvent);
        $form = $this->createForm(EventEncounterType::class, $eventEncounter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($eventEncounter);
            $this->entityManager->flush();

            for ($i = 0; $i < GuildEventTypeEnum::getMaxPlayersByType($guildEvent->getType()); $i++) {
                /** @var PlayerSlot $playerSlot */
                $playerSlot = $form->get("playerSlot$i")->getData();
                $playerSlot->setEventEncounter($eventEncounter);
                $this->entityManager->persist($playerSlot);
            }

            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "Un combat a bien été ajouté à l'événement."
            );

            return $this->redirectToRoute('guild_event_show', [
                'id' => $guildEvent->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->redirectToRoute('guild_event_show', ['id' => $guildEvent->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    final public function editForEvent(Request $request, EventEncounter $eventEncounter): Response
    {
        $form = $this->createForm(EventEncounterType::class, $eventEncounter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "Le combat a bien été modifié."
            );

            return $this->redirectToRoute('guild_event_show', [
                'id' => $eventEncounter->getGuildEvent()?->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('guild_event/edit_event_encounter.html.twig', [
            'form' => $form->createView(),
            'guild_event' => $eventEncounter->getGuildEvent(),
            'max_player_slots' => GuildEventTypeEnum::getMaxPlayersByType($eventEncounter->getGuildEvent()?->getType())
        ]);
    }
}
