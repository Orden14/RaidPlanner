<?php

namespace App\Controller;

use App\Entity\GuildEvent;
use App\Enum\RolesEnum;
use App\Form\GuildEventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::MEMBER->value)]
#[Route('/planning', name: 'calendar_')]
class CalendarController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    final public function index(): Response
    {
        $form = $this->createForm(GuildEventType::class, new GuildEvent());

        return $this->render('components/calendar/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
