<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\User;
use App\Enum\BuildStatusEnum;
use App\Enum\RolesEnum;
use App\Form\BuildType;
use App\Repository\BuildCategoryRepository;
use App\Repository\BuildMessageRepository;
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

#[IsGranted(RolesEnum::TRIAL->value)]
#[Route('/build', name: 'build_')]
class BuildController extends AbstractController
{
    public function __construct(
        private readonly FormFlashHelper $formFlashHelper,
        private readonly BuildRepository $buildRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly BuildMessageRepository $buildMessageRepository,
        private readonly BuildCategoryRepository $buildCategoryRepository,
        private readonly SpecializationRepository $specializationRepository
    ) {}

    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    final public function index(Request $request): Response
    {
        $build = new Build();
        $form = $this->createForm(BuildType::class, $build, [
            'action' => $this->generateUrl('build_new'),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        return $this->render('build/index.html.twig', [
            'form' => $form->createView(),
            'categories' => $this->buildCategoryRepository->findAll(),
            'builds' => $this->buildRepository->findAll(),
            'specializations' => $this->specializationRepository->findAllOrderedByJob(false),
        ]);
    }

    #[IsGranted(RolesEnum::MEMBER->value)]
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    final public function new(Request $request): Response
    {
        $build = new Build();
        $form = $this->createForm(BuildType::class, $build);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $currentUser */
            $currentUser = $this->getUser();

            $build->setLastEditedAt(new DateTime());
            $build->setCreator($currentUser);

            $this->entityManager->persist($build);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "Le build {$build->getName()} a bien été créé"
            );

            return $this->redirectToRoute('build_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('build/index.html.twig', [
            'form' => $form->createView(),
            'builds' => $this->buildRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET', 'POST'])]
    final public function show(Request $request, Build $build): Response
    {
        $form = $this->createForm(BuildType::class, $build, [
            'action' => $this->generateUrl('build_new'),
            'method' => 'POST',

        ]);
        $form->handleRequest($request);

        return $this->render('build/show.html.twig', [
            'form' => $form->createView(),
            'build' => $build,
            'messages' => $this->buildMessageRepository->findBy(['build' => $build])
        ]);
    }

    #[IsGranted(RolesEnum::MEMBER->value)]
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
                "Le build {$build->getName()} a bien été modifié"
            );

            return $this->redirectToRoute('build_show', [
                'id' => $build->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('build/show.html.twig', [
            'form' => $form->createView(),
            'build' => $build,
            'messages' => $this->buildMessageRepository->findBy(['build' => $build])
        ]);
    }

    #[IsGranted(RolesEnum::ADMIN->value)]
    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    final public function delete(Request $request, Build $build): Response
    {
        if ($this->isCsrfTokenValid('delete'.$build->getId(), $request->getPayload()->get('_token'))) {
            $this->entityManager->remove($build);
            $this->entityManager->flush();
        }

        $this->addFlash(
            'success',
            "Le build {$build->getName()} a bien été supprimé"
        );

        return $this->redirectToRoute('build_index', [], Response::HTTP_SEE_OTHER);
    }

    #[IsGranted(RolesEnum::MEMBER->value)]
    #[Route('/status/{id}/{statusString}', name: 'status', methods: ['GET', 'POST'])]
    final public function setStatus(int $id, string $statusString): Response
    {
        $build = $this->buildRepository->find($id);

        if (!$build) {
            throw $this->createNotFoundException('No build found for id '.$id);
        }

        $status = BuildStatusEnum::getStatusFromValue($statusString);

        $build->setStatus($status);
        $this->entityManager->flush();

        return $this->redirectToRoute('build_show', ['id' => $id], Response::HTTP_SEE_OTHER);
    }
}
