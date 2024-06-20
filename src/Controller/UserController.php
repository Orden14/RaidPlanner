<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Enum\RolesEnum;
use App\Form\Security\ChangePasswordType;
use App\Form\UserEditProfileType;
use App\Form\UserEditType;
use App\Repository\EventAttendanceRepository;
use App\Repository\UserRepository;
use App\Util\File\FileManager;
use App\Util\Form\FormFlashHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('', name: 'user_')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly FileManager $fileManager,
        private readonly UserRepository $userRepository,
        private readonly FormFlashHelper $formFlashHelper,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventAttendanceRepository $eventAttendanceRepository,
    ) {
    }

    #[IsGranted(RolesEnum::ADMIN->value)]
    #[Route('/members', name: 'members', methods: ['GET', 'POST'])]
    public function members(): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findAllMembers(),
            'title' => 'Membres',
        ]);
    }

    #[IsGranted(RolesEnum::ADMIN->value)]
    #[Route('/nouveaux_comptes', name: 'guests', methods: ['GET', 'POST'])]
    public function guests(): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findAllGuests(),
            'title' => 'Nouveaux comptes',
        ]);
    }

    #[IsGranted(RolesEnum::ADMIN->value)]
    #[Route('/user/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $route = $user->getRole() === RolesEnum::GUEST ? 'user_guests' : 'user_members';

        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRole($form->get('role')->getData());

            $this->addFlash(
                'success',
                "L'utilisateur {$user->getUsername()} a bien été modifié"
            );

            $this->entityManager->flush();

            return $this->redirectToRoute($route, [], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[IsGranted(RolesEnum::GUEST->value)]
    #[Route('/profile', name: 'profile', methods: ['GET', 'POST'])]
    public function profile(Request $request): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $form = $this->createForm(UserEditProfileType::class, $currentUser, [
            'action' => $this->generateUrl('user_profile_edit'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        $changePasswordForm = $this->createForm(ChangePasswordType::class, null, [
            'action' => $this->generateUrl('app_change_password'),
            'method' => 'POST',
        ]);
        $changePasswordForm->handleRequest($request);

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
            'change_password_form' => $changePasswordForm->createView(),
            'user' => $currentUser,
            'attendances' => $this->eventAttendanceRepository->findAllUpcomingAttendancesByTypesforPlayer($currentUser, [AttendanceTypeEnum::PLAYER, AttendanceTypeEnum::BACKUP]),
        ]);
    }

    #[IsGranted(RolesEnum::GUEST->value)]
    #[Route('/profile/edit', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function editProfile(Request $request): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $form = $this->createForm(UserEditProfileType::class, $currentUser);
        $form->handleRequest($request);

        $changePasswordForm = $this->createForm(ChangePasswordType::class, null, [
            'action' => $this->generateUrl('app_change_password'),
            'method' => 'POST',
        ]);
        $changePasswordForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilePicture = $form->get('profilePicture')->getData();
            if ($profilePicture) {
                $this->fileManager->removeFile($currentUser->getProfilePicture(), $this->getParameter('profile_picture_directory'));
                $currentUser->setProfilePicture($this->fileManager->uploadFile($profilePicture, $this->getParameter('profile_picture_directory')));
            }

            $this->addFlash(
                'success',
                'Votre profil a bien été modifié'
            );

            $this->entityManager->flush();

            return $this->redirectToRoute('user_profile', [], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
            'change_password_form' => $changePasswordForm->createView(),
            'user' => $currentUser,
            'attendances' => $this->eventAttendanceRepository->findAllUpcomingAttendancesByTypesforPlayer($currentUser, [AttendanceTypeEnum::PLAYER, AttendanceTypeEnum::BACKUP]),
        ]);
    }
}
