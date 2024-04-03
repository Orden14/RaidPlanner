<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\Specialization;
use App\Enum\RolesEnum;
use App\Form\SpecializationType;
use App\Repository\SpecializationRepository;
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

#[IsGranted(RolesEnum::ADMIN->value)]
#[Route('/specialisation', name: 'specialization_')]
class SpecializationController extends AbstractController
{
    const string SPECIALIZATION_INDEX_TEMPLATE = 'specialization/index.html.twig';

    public function __construct(
        private readonly FileManager $fileManager,
        private readonly FormFlashHelper $formFlashHelper,
        private readonly EntityManagerInterface $entityManager,
        private readonly SpecializationRepository $specializationRepository
    ) {}

    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    final public function index(Request $request): Response
    {
        $specialization = new Specialization();
        $form = $this->createForm(SpecializationType::class, $specialization, [
            'action' => $this->generateUrl('specialization_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        return $this->render(self::SPECIALIZATION_INDEX_TEMPLATE, [
            'form' => $form->createView(),
            'specializations' => $this->specializationRepository->findAllOrderedByJob()
        ]);
    }

    #[Route('/sort/{id}', name: 'show_by_job', methods: ['GET', 'POST'])]
    final public function showByJob(Job $job, Request $request): Response
    {
        $specializations = $this->specializationRepository->findBy(['job' => $job]);
        $specialization = new Specialization();
        $form = $this->createForm(SpecializationType::class, $specialization, [
            'action' => $this->generateUrl('specialization_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        return $this->render(self::SPECIALIZATION_INDEX_TEMPLATE, [
            'fromJobView' => true,
            'form' => $form->createView(),
            'specializations' => $specializations,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['POST'])]
    final public function new(Request $request): Response
    {
        $specialization = new Specialization();
        $form = $this->createForm(SpecializationType::class, $specialization);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $icon = $form->get('icon')->getData();
            if ($icon) {
                $newFileName = $this->fileManager->uploadFile($icon, $this->getParameter('icon_directory'));
                $specialization->setIcon($newFileName);
            }

            $this->entityManager->persist($specialization);
            $this->entityManager->flush();

            return $this->redirectToRoute('specialization_index', [], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render(self::SPECIALIZATION_INDEX_TEMPLATE, [
            'form' => $form,
            'specializations' => $this->specializationRepository->findAllOrderedByJob()
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

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('specialization/edit.html.twig', [
            'specialization' => $specialization,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    final public function delete(Request $request, Specialization $specialization): Response
    {
        if ($this->isCsrfTokenValid('delete'.$specialization->getId(), $request->getPayload()->get('_token'))) {
            if ($specialization->getIcon()) {
                $this->fileManager->removeFile($specialization->getIcon(), $this->getParameter('icon_directory'));
            }

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
