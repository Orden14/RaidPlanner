<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\RolesEnum;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use App\Util\Form\FormFlashHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('', name: 'user_')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository         $userRepository,
        private readonly FormFlashHelper        $formFlashHelper,
    ) {}

    #[IsGranted(RolesEnum::ADMIN->value)]
    #[Route('/members', name: 'members', methods: ['GET', 'POST'])]
    final public function members(): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findAllMembers(),
            'title' => 'Membres'
        ]);
    }

    #[IsGranted(RolesEnum::ADMIN->value)]
    #[Route('/nouveaux_comptes', name: 'guests', methods: ['GET', 'POST'])]
    final public function guests(): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findAllGuests(),
            'title' => 'Nouveaux comptes'
        ]);
    }

    #[IsGranted(RolesEnum::ADMIN->value)]
    #[Route('user/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    final public function edit(Request $request, User $user): Response
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
            'user' => $user
        ]);
    }
}
