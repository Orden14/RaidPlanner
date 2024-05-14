<?php

namespace App\Controller\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventEncounter;
use App\Enum\GuildEventStatusEnum;
use App\Enum\RolesEnum;
use App\Form\GuildEvent\EventEncounterType;
use App\Form\GuildEvent\GuildEventType;
use App\Repository\NonActiveSlotRepository;
use App\Util\Form\FormFlashHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::OLD_MEMBER->value)]
#[Route('/event', name: 'guild_event_')]
class GuildEventController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface  $entityManager,
        private readonly FormFlashHelper         $formFlashHelper,
        private readonly NonActiveSlotRepository $nonActiveSlotRepository
    ) {}

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    final public function new(Request $request): Response
    {
        $guildEvent = new GuildEvent();
        $form = $this->createForm(GuildEventType::class, $guildEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($guildEvent);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "L'évènement {$guildEvent->getTitle()} a bien été créé"
            );

            return $this->redirectToRoute('guild_event_show', ['id' => $guildEvent->getId()], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('components/calendar/index.html.twig', [
            'form' => $form->createView(),
            'guild_event' => $guildEvent,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET', 'POST'])]
    final public function show(Request $request, GuildEvent $guildEvent): Response
    {
        $form = $this->createForm(GuildEventType::class, $guildEvent, [
            'action' => $this->generateUrl('guild_event_edit', ['id' => $guildEvent->getId()]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        $formAddEncounter = $this->createForm(EventEncounterType::class, new EventEncounter(), [
            'action' => $this->generateUrl('guild_event_encounter_add', ['guildEvent' => $guildEvent->getId()]),
            'method' => 'POST'
        ]);
        $formAddEncounter->handleRequest($request);

        return $this->render('guild_event/show.html.twig', [
            'form' => $form->createView(),
            'form_add_encounter' => $formAddEncounter->createView(),
            'guild_event' => $guildEvent,
            'event_encounters' => $guildEvent->getEventEncounters(),
            'backups' => $this->nonActiveSlotRepository->findBackupsByEvent($guildEvent->getId()),
            'absents' => $this->nonActiveSlotRepository->findAbsentsByEvent($guildEvent->getId()),
        ]);
    }

    #[IsGranted(RolesEnum::MEMBER->value)]
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    final public function edit(Request $request, GuildEvent $guildEvent): Response
    {
        $form = $this->createForm(GuildEventType::class, $guildEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "L'évènement {$guildEvent->getTitle()} a bien été modifié"
            );

            return $this->redirectToRoute('guild_event_show', [
                'id' => $guildEvent->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('build/show.html.twig', [
            'form' => $form->createView(),
            'guild_event' => $guildEvent,
        ]);
    }

    #[IsGranted(RolesEnum::ADMIN->value)]
    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    final public function delete(Request $request, GuildEvent $guildEvent): Response
    {
        if ($this->isCsrfTokenValid('delete' . $guildEvent->getId(), $request->getPayload()->get('_token'))) {
            $this->entityManager->remove($guildEvent);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('calendar_index', [], Response::HTTP_SEE_OTHER);
    }

    #[IsGranted(RolesEnum::ADMIN->value)]
    #[Route('/toggle/{id}', name: 'toggle_status', methods: ['GET', 'POST'])]
    final public function toggleStatus(GuildEvent $guildEvent): Response
    {
        $guildEvent->getStatus() === GuildEventStatusEnum::OPEN
            ? $guildEvent->setStatus(GuildEventStatusEnum::CANCELLED)
            : $guildEvent->setStatus(GuildEventStatusEnum::OPEN);

        $this->entityManager->flush();

        return $this->redirectToRoute('guild_event_show', ['id' => $guildEvent->getId()], Response::HTTP_SEE_OTHER);
    }
}
