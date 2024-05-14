<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\RolesEnum;
use App\Form\RegistrationType;
use App\Security\AppAuthenticator;
use App\Service\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class SecurityController extends AbstractController
{
    public function __construct(
        private readonly Security                    $security,
        private readonly UserService                 $userService,
        private readonly EntityManagerInterface      $entityManager,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly AuthenticationUtils         $authenticationUtils,
    ) {}

    #[Route(path: '/login', name: 'app_login')]
    public function login(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_home');
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->userService->setDefaultProfilePicture($user);
            $user->setRole(RolesEnum::GUEST);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->security->login($user, AppAuthenticator::class, 'main');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
