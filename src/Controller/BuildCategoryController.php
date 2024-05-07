<?php

namespace App\Controller;

use App\Entity\BuildCategory;
use App\Enum\RolesEnum;
use App\Form\BuildCategoryType;
use App\Repository\BuildCategoryRepository;
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
#[Route('/category', name: 'buildcategory_')]
class BuildCategoryController extends AbstractController
{
    private const string BUILD_CATEGORY_INDEX_TEMPLATE = 'build_category/index.html.twig';

    public function __construct(
        private readonly FileManager $fileManager,
        private readonly FormFlashHelper $formFlashHelper,
        private readonly EntityManagerInterface $entityManager,
        private readonly BuildCategoryRepository $buildCategoryRepository
    ) {}

    #[Route('/', name: 'index', methods: ['GET'])]
    final public function index(): Response
    {
        $buildCategory = new BuildCategory();
        $form = $this->createForm(BuildCategoryType::class, $buildCategory, [
            'action' => $this->generateUrl('buildcategory_new'),
            'method' => 'POST',
        ]);

        return $this->render(self::BUILD_CATEGORY_INDEX_TEMPLATE, [
            'form' => $form->createView(),
            'build_categories' => $this->buildCategoryRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    final public function new(Request $request): Response
    {
        $buildCategory = new BuildCategory();
        $form = $this->createForm(BuildCategoryType::class, $buildCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $icon = $form->get('icon')->getData();
            if ($icon) {
                $newFileName = $this->fileManager->uploadFile($icon, $this->getParameter('icon_directory'));
                $buildCategory->setIcon($newFileName);
            }

            foreach ($form->get('builds')->getData() as $build) {
                $build->addCategory($buildCategory);
            }

            $this->entityManager->persist($buildCategory);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "La catégorie {$buildCategory->getName()} a bien été créée"
            );

            return $this->redirectToRoute('buildcategory_index', [], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render(self::BUILD_CATEGORY_INDEX_TEMPLATE, [
            'form' => $form->createView(),
            'build_categories' => $this->buildCategoryRepository->findAll()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    final public function edit(Request $request, BuildCategory $buildCategory): Response
    {
        $form = $this->createForm(BuildCategoryType::class, $buildCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $icon = $form->get('icon')->getData();
            if ($icon) {
                $this->fileManager->removeFile($buildCategory->getIcon(), $this->getParameter('icon_directory'));
                $buildCategory->setIcon($this->fileManager->uploadFile($icon, $this->getParameter('icon_directory')));
            }

            $formBuilds = $form->get('builds')->getData()->toArray();
            $linkedBuilds = $buildCategory->getBuilds()->toArray();
            foreach ($linkedBuilds as $linkedBuild) {
                if (!in_array($linkedBuild, $formBuilds, true)) {
                    $linkedBuild->removeCategory($buildCategory);
                }
            }
            foreach ($formBuilds as $formBuild) {
                if (!in_array($formBuild, $linkedBuilds, true)) {
                    $formBuild->addCategory($buildCategory);
                }
            }

            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "La catégorie {$buildCategory->getName()} a bien été modifiée"
            );

            return $this->redirectToRoute('buildcategory_index', [], Response::HTTP_SEE_OTHER);
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->render('build_category/edit.html.twig', [
            'build_category' => $buildCategory,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    final public function delete(Request $request, BuildCategory $buildCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$buildCategory->getId(), $request->getPayload()->get('_token'))) {
            if ($buildCategory->getIcon()) {
                $this->fileManager->removeFile($buildCategory->getIcon(), $this->getParameter('icon_directory'));
            }

            $this->entityManager->remove($buildCategory);
            $this->entityManager->flush();
        }

        $this->addFlash(
            'success',
            "La catégorie {$buildCategory->getName()} a bien été supprimée"
        );

        return $this->redirectToRoute('buildcategory_index', [], Response::HTTP_SEE_OTHER);
    }
}
