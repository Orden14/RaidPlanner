<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\User;
use App\Enum\RolesEnum;
use App\Form\DefaultBuildType;
use App\Repository\BuildRepository;
use App\Repository\SpecializationRepository;
use App\Util\Form\FormFlashHelper;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::ADMIN->value)]
#[Route('/default_build', name: 'default_build_')]
class DefaultBuildController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface   $entityManager,
        private readonly FormFlashHelper          $formFlashHelper,
        private readonly BuildRepository          $buildRepository,
        private readonly SpecializationRepository $specializationRepository
    ) {}

    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    final public function defaultBuildsIndex(Request $request): Response
    {
        $defaultBuild = (new Build())->setSpecialization($this->specializationRepository->findDefaultSpecialization());
        $form = $this->createForm(DefaultBuildType::class, $defaultBuild, [
            'action' => $this->generateUrl('default_build_new'),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        return $this->render('build/default_build/index.html.twig', [
            'form' => $form->createView(),
            'builds' => $this->buildRepository->findByDefault(true),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    final public function newDefaultBuild(Request $request): Response
    {
        $defaultBuild = new Build();
        $form = $this->createForm(DefaultBuildType::class, $defaultBuild);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $currentUser */
            $currentUser = $this->getUser();

            $defaultBuild->setSpecialization($this->specializationRepository->findDefaultSpecialization());
            $defaultBuild->setLastEditedAt(new DateTime());
            $defaultBuild->setAuthor($currentUser);

            $this->entityManager->persist($defaultBuild);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "Le build par défaut {$defaultBuild->getName()} a bien été créé"
            );

            return $this->redirectToRoute('default_build_index', [], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('build/default_build/index.html.twig', [
            'form' => $form->createView(),
            'builds' => $this->buildRepository->findByDefault(true),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    final public function editDefaultBuild(Request $request, Build $build): Response
    {
        $form = $this->createForm(DefaultBuildType::class, $build);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $build->setLastEditedAt(new DateTime());
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "Le build par défaut {$build->getName()} a bien été modifié"
            );

            return $this->redirectToRoute('default_build_index', [], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('build/default_build/edit.html.twig', [
            'form' => $form->createView(),
            'build' => $build
        ]);
    }
}
