<?php

namespace App\Controller;

use App\Entity\Instance;
use App\Form\InstanceType;
use App\Repository\InstanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/instance')]
class InstanceController extends AbstractController
{
    #[Route('/', name: 'app_instance_index', methods: ['GET'])]
    public function index(InstanceRepository $instanceRepository): Response
    {
        return $this->render('instance/index.html.twig', [
            'instances' => $instanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_instance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $instance = new Instance();
        $form = $this->createForm(InstanceType::class, $instance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($instance);
            $entityManager->flush();

            return $this->redirectToRoute('app_instance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('instance/new.html.twig', [
            'instance' => $instance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_instance_show', methods: ['GET'])]
    public function show(Instance $instance): Response
    {
        return $this->render('instance/show.html.twig', [
            'instance' => $instance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_instance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Instance $instance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InstanceType::class, $instance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_instance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('instance/edit.html.twig', [
            'instance' => $instance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_instance_delete', methods: ['POST'])]
    public function delete(Request $request, Instance $instance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$instance->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($instance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_instance_index', [], Response::HTTP_SEE_OTHER);
    }
}
