<?php

namespace App\Controller;

use App\Entity\Job;
use App\Enum\RolesEnum;
use App\Form\JobType;
use App\Repository\JobRepository;
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
#[Route('/job', name: 'job_')]
final class JobController extends AbstractController
{
    public function __construct(
        private readonly FileManager $fileManager,
        private readonly FormFlashHelper $formFlashHelper,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(JobRepository $jobRepository): Response
    {
        return $this->render('job/index.html.twig', [
            'jobs' => $jobRepository->findAllWithoutDefault(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Job $job): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $icon = $form->get('icon')->getData();
            if ($icon) {
                $this->fileManager->removeFile($job->getIcon(), $this->getParameter('icon_directory'));
                $job->setIcon($this->fileManager->uploadFile($icon, $this->getParameter('icon_directory')));
            }

            $this->entityManager->persist($job);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "La classe {$job->getName()} a bien été modifiée"
            );

            return $this->redirectToRoute('job_index', [], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('job/edit.html.twig', [
            'form' => $form->createView(),
            'job' => $job,
        ]);
    }
}
