<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\BuildMessage;
use App\Entity\User;
use App\Enum\LogTypeEnum;
use App\Enum\RolesEnum;
use App\Util\Log\LogManager;
use DateTime;
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
        private readonly LogManager $logManager,
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/post/{id}', name: 'post', methods: ['POST'])]
    final public function post(Build $build, Request $request): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $message = new BuildMessage();
        $message->setAuthor($currentUser);
        $message->setContent($request->request->get('messageContent'));
        $message->setBuild($build);
        $message->setPostedAt(new DateTime());

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        $this->logManager->log(LogTypeEnum::BUILD_MESSAGE_NEW, $message->getBuild()?->getId());
        $this->addFlash('success', 'Votre message a été publié.');

        return $this->redirectToRoute('build_show', ['id' => $build->getId()]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    final public function delete(Request $request, BuildMessage $buildMessage): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        /** @var Build $build */
        $build = $buildMessage->getBuild();


        if ($currentUser->getRole() !== RolesEnum::ADMIN && $currentUser !== $buildMessage->getAuthor()) {
            $this->addFlash('danger', 'Vous n\'avez pas les droits pour supprimer ce message.');
            return $this->redirectToRoute('build_show', ['id' => $build->getId()]);
        }

        if ($this->isCsrfTokenValid('delete'.$buildMessage->getId(), $request->getPayload()->get('_token'))) {
            $this->entityManager->remove($buildMessage);
            $this->entityManager->flush();

            $this->logManager->log(LogTypeEnum::BUILD_MESSAGE_DELETE, $buildMessage->getAuthor()?->getId(), $buildMessage->getBuild()?->getName());
            $this->addFlash(
                'success',
                "Message supprimé avec succès"
            );
        }

        return $this->redirectToRoute('build_show', [
            'id' => $build->getId(),
        ], Response::HTTP_SEE_OTHER);
    }
}
