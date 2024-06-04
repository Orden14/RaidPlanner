<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Enum\RolesEnum;
use App\Form\Security\ChangePasswordType;
use App\Form\Security\RegistrationType;
use App\Security\AppAuthenticator;
use App\Service\User\UserProfileService;
use App\Util\Form\FormFlashHelper;
use App\Util\Security\RegistrationTokenHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class SecurityController extends AbstractController
{
    public function __construct(
        private readonly Security                    $security,
        private readonly EntityManagerInterface      $entityManager,
        private readonly FormFlashHelper             $formFlashHelper,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UserProfileService          $userProfileService,
        private readonly AuthenticationUtils         $authenticationUtils,
        private readonly RegistrationTokenHandler    $registrationTokenHandler,
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

        if ($form->isSubmitted() && $form->isValid() && $this->registrationTokenHandler->checkToken($form->get('registrationToken')->getData())) {
            $this->registrationTokenHandler->incrementTokenUsage($form->get('registrationToken')->getData());

            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->userProfileService->setDefaultProfilePicture($user);
            $user->setRole(RolesEnum::GUEST);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->security->login($user, AppAuthenticator::class, 'main');
        }

        if ($form->isSubmitted() && !$this->registrationTokenHandler->checkToken($form->get('registrationToken')->getData())) {
            $form->get('registrationToken')->addError(new FormError("Token d'enregistrement invalide"));
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[IsGranted(RolesEnum::GUEST->value)]
    #[Route('/user/change_password', name: 'app_change_password')]
    public function changePassword(Request $request): Response
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();

            if (!$this->userPasswordHasher->isPasswordValid($user, $form->get('currentPassword')->getData())) {
                $form->addError(new FormError('Le mot de passe saisi est incorrect'));
            } else {
                $user->setPassword(
                    $this->userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $this->entityManager->flush();
                $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
            }
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->redirectToRoute('user_profile');
    }
}
