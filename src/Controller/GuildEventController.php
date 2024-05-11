<?php

namespace App\Controller;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\GuildEventSlot;
use App\Enum\GuildEventTypeEnum;
use App\Enum\RolesEnum;
use App\Form\GuildEventType;
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
#[Route('/event', name: 'guild_event_')]
class GuildEventController extends AbstractController
{
    public function __construct(
        private readonly FormFlashHelper $formFlashHelper,
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    final public function new(Request $request): Response
    {
        $guildEvent = new GuildEvent();
        $form = $this->createForm(GuildEventType::class, $guildEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($guildEvent);

            for ($i = 0; $i < GuildEventTypeEnum::getMaxPlayersByType($guildEvent->getType()); $i++) {
                $guildEventSlot = (new GuildEventSlot())->setGuildEvent($guildEvent);
                $this->entityManager->persist($guildEventSlot);
            }

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

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    final public function show(GuildEvent $guildEvent): Response
    {
        return $this->render('guild_event/show.html.twig', [
            'guild_event' => $guildEvent,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    final public function delete(Request $request, GuildEvent $guildEvent): Response
    {
        if ($this->isCsrfTokenValid('delete'.$guildEvent->getId(), $request->getPayload()->get('_token'))) {
            $this->entityManager->remove($guildEvent);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('calendar_index', [], Response::HTTP_SEE_OTHER);
    }
}
