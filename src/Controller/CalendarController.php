<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/planning', name: 'app_calendar_')]
class CalendarController extends AbstractController
{
    #[Route('/', name: 'index')]
    final public function index(): Response
    {
        return $this->render('components/calendar/index.html.twig');
    }
}
