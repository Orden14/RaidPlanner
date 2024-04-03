<?php

namespace App\Controller;

use App\Entity\Job;
use App\Enum\RolesEnum;
use App\Form\JobType;
use App\Repository\JobRepository;
use App\Util\File\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::ADMIN->value)]
#[Route('/job', name: 'job_')]
class JobController extends AbstractController
{
    public function __construct(
        private readonly FileManager $fileManager,
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/', name: 'index', methods: ['GET'])]
    final public function index(JobRepository $jobRepository): Response
    {
        return $this->render('job/index.html.twig', [
            'jobs' => $jobRepository->findAll()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    final public function edit(Request $request, Job $job): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $icon = $form->get('icon')->getData();

            if ($icon) {
                if ($job->getIcon()) {
                    $this->fileManager->removeFile($job->getIcon(), $this->getParameter('icon_directory'));
                }

                $newFileName = $this->fileManager->uploadFile($icon, $this->getParameter('icon_directory'));
                $job->setIcon($newFileName);
            }

            $this->entityManager->persist($job);
            $this->entityManager->flush();

            return $this->redirectToRoute('job_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job/edit.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }
}
