<?php

namespace App\Controller;

use App\Enum\RolesEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[isGranted(RolesEnum::GUEST->value)]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    final public function index(): Response
    {
        if (!$this->isGranted(RolesEnum::MEMBER->value)) {
            return $this->redirectToRoute('app_guest');
        }

        return $this->render('home/index.html.twig');
    }

    #[Route('/guest', name: 'app_guest')]
    final public function guestPage(): Response
    {
        if ($this->isGranted(RolesEnum::MEMBER->value)) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/guest_page.html.twig');
    }
}
