<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\Specialization;
use App\Enum\RolesEnum;
use App\Form\SpecializationType;
use App\Repository\JobRepository;
use App\Repository\SpecializationRepository;
use App\Util\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::ADMIN->value)]
#[Route('/specialisation', name: 'specialization_')]
class SpecializationController extends AbstractController
{
    public function __construct(
        private readonly FileManager $fileManager,
        private readonly JobRepository $jobRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SpecializationRepository $specializationRepository
    ) {}

    #[Route('/', name: 'index', methods: ['GET'])]
    final public function index(): Response
    {
        return $this->render('specialization/index.html.twig', [
            'specializations' => $this->specializationRepository->findAllOrderedByJob(),
        ]);
    }

    #[Route('/sort/{id}', name: 'show_by_job', methods: ['GET'])]
    final public function showByJob(Job $job): Response
    {
        $specializations = $this->specializationRepository->findBy(['job' => $job]);

        return $this->render('specialization/index.html.twig', [
            'fromJobView' => true,
            'specializations' => $specializations,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    final public function add(Request $request): Response
    {
        $specialization = new Specialization();
        $form = $this->createForm(SpecializationType::class, $specialization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($specialization);
            $this->entityManager->flush();

            return $this->redirectToRoute('specialization_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('specialization/new.html.twig', [
            'specialization' => $specialization,
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    final public function edit(Request $request, Specialization $specialization): Response
    {
        $form = $this->createForm(SpecializationType::class, $specialization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $icon = $form->get('icon')->getData();

            if ($icon) {
                if ($specialization->getIcon()) {
                    $this->fileManager->removeFile($specialization->getIcon(), $this->getParameter('icon_directory'));
                }

                $newFileName = $this->fileManager->uploadFile($icon, $this->getParameter('icon_directory'));
                $specialization->setIcon($newFileName);
            }

            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "La spécialisation {$specialization->getName()} a bien été modifiée"
            );

            return $this->redirectToRoute('specialization_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('specialization/edit.html.twig', [
            'specialization' => $specialization,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    final public function delete(Request $request, Specialization $specialization): Response
    {
        if ($this->isCsrfTokenValid('delete'.$specialization->getId(), $request->getPayload()->get('_token'))) {
            $this->entityManager->remove($specialization);
            $this->entityManager->flush();
        }

        $this->addFlash(
            'success',
            "La spécialisation {$specialization->getName()} a bien été supprimée"
        );

        return $this->redirectToRoute('specialization_index', [], Response::HTTP_SEE_OTHER);
    }
}
