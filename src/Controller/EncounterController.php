<?php

namespace App\Controller;

use App\Entity\Encounter;
use App\Enum\RolesEnum;
use App\Form\EncounterType;
use App\Repository\EncounterRepository;
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
#[Route('/encounter', name: 'encounter_')]
class EncounterController extends AbstractController
{
    public function __construct(
        private readonly FormFlashHelper $formFlashHelper,
        private readonly EntityManagerInterface $entityManager,
        private readonly EncounterRepository $encounterRepository
    ) {}

    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    final public function index(Request $request): Response
    {
        $encounter = new Encounter();
        $form = $this->createForm(EncounterType::class, $encounter, [
            'action' => $this->generateUrl('encounter_new'),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        return $this->render('encounter/index.html.twig', [
            'form' => $form->createView(),
            'encounters' => $this->encounterRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    final public function new(Request $request): Response
    {
        $encounter = new Encounter();
        $form = $this->createForm(EncounterType::class, $encounter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($encounter);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "{$encounter->getName()} a bien été créé"
            );

            /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
            $formErrors = $form->getErrors(true, false);
            $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

            return $this->redirectToRoute('encounter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('encounter/index.html.twig', [
            'form' => $form->createView(),
            'instances' => $this->encounterRepository->findAll(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    final public function edit(Request $request, Encounter $encounter): Response
    {
        $form = $this->createForm(EncounterType::class, $encounter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "{$encounter->getName()} a bien été modifié"
            );

            return $this->redirectToRoute('encounter_index', [], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('encounter/edit.html.twig', [
            'form' => $form->createView(),
            'encounter' => $encounter,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    final public function delete(Request $request, Encounter $encounter): Response
    {
        if ($this->isCsrfTokenValid('delete'.$encounter->getId(), $request->getPayload()->get('_token'))) {
            $this->entityManager->remove($encounter);
            $this->entityManager->flush();
        }

        $this->addFlash(
            'success',
            "{$encounter->getName()} a bien été supprimé"
        );

        return $this->redirectToRoute('encounter_index', [], Response::HTTP_SEE_OTHER);
    }
}
