<?php

namespace App\Controller\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventBattle;
use App\Enum\InstanceTypeEnum;
use App\Enum\RolesEnum;
use App\Form\GuildEvent\EventBattleType;
use App\Service\GuildEvent\SlotService;
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
#[Route('/event-battle', name: 'guild_event_battle_')]
class EventBattleController extends AbstractController
{
    public function __construct(
        private readonly SlotService            $slotService,
        private readonly EntityManagerInterface $entityManager,
        private readonly FormFlashHelper        $formFlashHelper,
    ) {}

    #[Route('/add/{guildEvent}', name: 'add', methods: ['GET', 'POST'])]
    final public function addToEvent(Request $request, GuildEvent $guildEvent): Response
    {
        if (!$guildEvent->canMembersManageEvent() && !$this->isGranted(RolesEnum::ADMIN->value)) {
            return $this->redirectToRoute('guild_event_show', ['id' => $guildEvent->getId()], Response::HTTP_SEE_OTHER);
        }

        $eventBattle = (new EventBattle())->setGuildEvent($guildEvent);
        $form = $this->createForm(EventBattleType::class, $eventBattle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($eventBattle);
            $this->entityManager->flush();
            $this->slotService->createSlotsForEventBattle($eventBattle, $this->slotService->getPlayerSlotsFromForm($form));


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
    final public function editForEvent(Request $request, EventBattle $eventBattle): Response
    {
        /** @var GuildEvent $guildEvent */
        $guildEvent = $eventBattle->getGuildEvent();
        if (!$guildEvent->canMembersManageEvent() && !$this->isGranted(RolesEnum::ADMIN->value)) {
            return $this->redirectToRoute('guild_event_show', ['id' => $guildEvent->getId()], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(EventBattleType::class, $eventBattle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "Le combat a bien été modifié."
            );

            return $this->redirectToRoute('guild_event_show', [
                'id' => $eventBattle->getGuildEvent()?->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('guild_event/edit_event_battle.html.twig', [
            'form' => $form->createView(),
            'guild_event' => $eventBattle->getGuildEvent(),
            'max_player_slots' => InstanceTypeEnum::getMaxPlayersByType($eventBattle->getGuildEvent()?->getType())
        ]);
    }
}
