<?php

namespace App\Controller;

use App\Entity\GuildEvent;
use App\Enum\RolesEnum;
use App\Form\GuildEventType;
use App\Repository\GuildEventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::MEMBER->value)]
#[Route('/event', name: 'guild_event_')]
class GuildEventController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}
    #[Route('/', name: 'index', methods: ['GET'])]
    final public function index(GuildEventRepository $guildEventRepository): Response
    {
        return $this->render('guild_event/index.html.twig', [
            'guild_events' => $guildEventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    final public function new(Request $request): Response
    {
        $guildEvent = new GuildEvent();
        $form = $this->createForm(GuildEventType::class, $guildEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($guildEvent);
            $this->entityManager->flush();

            return $this->redirectToRoute('guild_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('guild_event/new.html.twig', [
            'guild_event' => $guildEvent,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    final public function show(GuildEvent $guildEvent): Response
    {
        return $this->render('guild_event/show.html.twig', [
            'guild_event' => $guildEvent,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    final public function edit(Request $request, GuildEvent $guildEvent): Response
    {
        $form = $this->createForm(GuildEventType::class, $guildEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('guild_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('guild_event/edit.html.twig', [
            'form' => $form->createView(),
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

        return $this->redirectToRoute('guild_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
