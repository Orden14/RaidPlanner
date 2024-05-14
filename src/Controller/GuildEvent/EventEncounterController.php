<?php

namespace App\Controller\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventEncounter;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Enum\GuildEventTypeEnum;
use App\Enum\RolesEnum;
use App\Form\GuildEvent\EventEncounterType;
use App\Util\Form\FormFlashHelper;
use DateTime;
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
    final public function addEncounterToEvent(Request $request, GuildEvent $guildEvent): Response
    {
        $eventEncounter = new EventEncounter();
        $form = $this->createForm(EventEncounterType::class, $eventEncounter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventEncounter
                ->setGuildEvent($guildEvent)
                ->setCreatedAt(new DateTime());

            $this->entityManager->persist($eventEncounter);

            for ($i = 0; $i < GuildEventTypeEnum::getMaxPlayersByType($guildEvent->getType()); $i++) {
                $eventSlot = (new PlayerSlot())->setEventEncounter($eventEncounter);

                $this->entityManager->persist($eventSlot);
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
}
