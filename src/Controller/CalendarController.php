<?php

namespace App\Controller;

use App\Entity\GuildEvent;
use App\Enum\RolesEnum;
use App\Form\GuildEvent\GuildEventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::OLD_MEMBER->value)]
#[Route('/planning', name: 'calendar_')]
class CalendarController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    final public function index(Request $request): Response
    {
        $guildEvent = new GuildEvent();
        $form = $this->createForm(GuildEventType::class, $guildEvent, [
            'action' => $this->generateUrl('guild_event_new'),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        return $this->render('components/calendar/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
