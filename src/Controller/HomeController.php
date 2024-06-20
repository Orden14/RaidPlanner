<?php

namespace App\Controller;

use App\Enum\RolesEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class HomeController extends AbstractController
{
    #[IsGranted(RolesEnum::GUEST->value)]
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {
        if (!$this->isGranted(RolesEnum::OLD_MEMBER->value)) {
            return $this->redirectToRoute('app_guest');
        }

        return $this->redirectToRoute('calendar_index');
    }

    #[IsGranted(RolesEnum::GUEST->value)]
    #[Route('/guest', name: 'app_guest', methods: ['GET'])]
    public function guestPage(): Response
    {
        if ($this->isGranted(RolesEnum::MEMBER->value)) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/guest_page.html.twig');
    }

    #[Route('/privacy', name: 'app_privacy', methods: ['GET'])]
    public function privacy(): Response
    {
        return $this->render('privacy/privacy.html.twig');
    }
}
