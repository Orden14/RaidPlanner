<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\BuildMessage;
use App\Entity\User;
use App\Enum\RolesEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::TRIAL->value)]
#[Route('/buildMessage', name: 'build_message_')]
class BuildMessageController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/post/{id}', name: 'post', methods: ['POST'])]
    final public function postMessage(Build $build, Request $request): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $message = new BuildMessage();
        $message->setAuthor($currentUser);
        $message->setContent($request->request->get('messageContent'));
        $message->setBuild($build);

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        $this->addFlash('success', 'Votre message a été publié.');

        return $this->redirectToRoute('build_show', ['id' => $build->getId()]);
    }
}
