<?php

namespace App\Controller;

use App\DTO\Entity\UserDTO;
use App\Factory\UserFactory;
use App\Form\RegistrationType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    final public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('app_home');
         }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    final public function logout(): Response
    {
        return $this->redirectToRoute('app_home');
    }

    #[Route('/register', name: 'app_register')]
    final public function register(
        Request $request,
        Security $security,
        UserFactory $userFactory,
        EntityManagerInterface $entityManager
    ): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $userDto = new UserDTO();
        $form = $this->createForm(RegistrationType::class, $userDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userDto->setPassword($form->get('plainPassword')->getData());
            $user = $userFactory->create($userDto);
            $entityManager->persist($user);
            $entityManager->flush();

            return $security->login($user, AppAuthenticator::class, 'main');
        }


        return $this->render('security/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
