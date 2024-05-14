<?php

namespace App\Controller;

use App\Entity\Instance;
use App\Enum\RolesEnum;
use App\Form\InstanceType;
use App\Repository\InstanceRepository;
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
#[Route('/instance', name: 'instance_')]
class InstanceController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FormFlashHelper        $formFlashHelper,
        private readonly InstanceRepository     $instanceRepository
    ) {}

    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    final public function index(Request $request): Response
    {
        $instance = new Instance();
        $form = $this->createForm(InstanceType::class, $instance, [
            'action' => $this->generateUrl('instance_new'),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        return $this->render('instance/index.html.twig', [
            'form' => $form->createView(),
            'instances' => $this->instanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    final public function new(Request $request): Response
    {
        $instance = new Instance();
        $form = $this->createForm(InstanceType::class, $instance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($instance);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "L'instance {$instance->getName()} a bien été créée"
            );

            return $this->redirectToRoute('instance_index', [], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('build/index.html.twig', [
            'form' => $form->createView(),
            'instances' => $this->instanceRepository->findAll(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    final public function edit(Request $request, Instance $instance): Response
    {
        $form = $this->createForm(InstanceType::class, $instance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "L'instance {$instance->getName()} a bien été modifiée"
            );

            return $this->redirectToRoute('instance_index', [], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('instance/edit.html.twig', [
            'form' => $form->createView(),
            'instance' => $instance,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    final public function delete(Request $request, Instance $instance): Response
    {
        if ($this->isCsrfTokenValid('delete' . $instance->getId(), $request->getPayload()->get('_token'))) {
            $this->entityManager->remove($instance);
            $this->entityManager->flush();
        }

        $this->addFlash(
            'success',
            "L'instance {$instance->getName()} a bien été supprimée"
        );

        return $this->redirectToRoute('instance_index', [], Response::HTTP_SEE_OTHER);
    }
}
