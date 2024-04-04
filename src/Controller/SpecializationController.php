<?php

namespace App\Controller;

use App\DTO\Entity\SpecializationDTO;
use App\Entity\Job;
use App\Entity\Specialization;
use App\Enum\RolesEnum;
use App\Factory\SpecializationFactory;
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
    private const string SPECIALIZATION_INDEX_TEMPLATE = 'specialization/index.html.twig';

    public function __construct(
        private readonly FileManager $fileManager,
        private readonly SpecializationFactory $specializationFactory,
        private readonly FormFlashHelper $formFlashHelper,
        private readonly EntityManagerInterface $entityManager,
        private readonly SpecializationRepository $specializationRepository
    ) {}

    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    final public function index(Request $request): Response
    {
        $specialization = new SpecializationDTO();
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
        $specialization = new SpecializationDTO();
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
        $specializationDto = new SpecializationDTO();
        $form = $this->createForm(SpecializationType::class, $specializationDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $specializationDto->setIcon($form->get('icon')->getData());
            $this->entityManager->persist($this->specializationFactory->create($specializationDto));
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
        $specializationDto = (new SpecializationDTO())
            ->setFromObject($specialization)
        ;
        $form = $this->createForm(SpecializationType::class, $specializationDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $icon = $form->get('icon')->getData();
            if ($icon) {
                $this->fileManager->removeFile($specialization->getIcon(), $this->getParameter('icon_directory'));
                $specialization->setIcon($this->fileManager->uploadFile($icon, $this->getParameter('icon_directory')));
            }

            $specialization->setName($specializationDto->getName())
                ->setJob($specializationDto->getJob())
            ;

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
