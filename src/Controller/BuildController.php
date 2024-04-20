<?php

namespace App\Controller;

use App\Entity\Build;
use App\Enum\RolesEnum;
use App\Form\BuildType;
use App\Repository\BuildCategoryRepository;
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

#[IsGranted(RolesEnum::MEMBER->value)]
#[Route('/build', name: 'build_')]
class BuildController extends AbstractController
{
    public function __construct(
        private readonly FormFlashHelper $formFlashHelper,
        private readonly BuildRepository $buildRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly BuildCategoryRepository $buildCategoryRepository,
        private readonly SpecializationRepository $specializationRepository
    ) {}

    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    final public function index(Request $request): Response
    {
        $build = new Build();
        $form = $this->createForm(BuildType::class, $build, [
            'action' => $this->generateUrl('build_new'),
            'method' => 'POST',

        ]);
        $form->handleRequest($request);

        return $this->render('build/index.html.twig', [
            'form' => $form->createView(),
            'builds' => $this->buildRepository->findAll(),
            'categories' => $this->buildCategoryRepository->findAll(),
            'specializations' => $this->specializationRepository->findAllOrderedByJob(false),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    final public function new(Request $request): Response
    {
        $build = new Build();
        $form = $this->createForm(BuildType::class, $build);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $build->setLastEditedAt(new DateTime());

            $this->entityManager->persist($build);
            $this->entityManager->flush();

            return $this->redirectToRoute('build_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('build/index.html.twig', [
            'form' => $form,
            'builds' => $this->buildRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    final public function show(Build $build): Response
    {
        return $this->render('build/show.html.twig', [
            'build' => $build,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    final public function edit(Request $request, Build $build): Response
    {
        $form = $this->createForm(BuildType::class, $build);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $build->setLastEditedAt(new DateTime());

            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "Le build {$build->getName()} a bien été modifiée"
            );

            return $this->redirectToRoute('build_index', [], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('build/edit.html.twig', [
            'form' => $form,
            'build' => $build
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    final public function delete(Request $request, Build $build): Response
    {
        if ($this->isCsrfTokenValid('delete'.$build->getId(), $request->getPayload()->get('_token'))) {
            $this->entityManager->remove($build);
            $this->entityManager->flush();
        }

        $this->addFlash(
            'success',
            "Le build {$build->getName()} a bien été supprimée"
        );

        return $this->redirectToRoute('build_index', [], Response::HTTP_SEE_OTHER);
    }
}
