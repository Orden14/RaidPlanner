<?php

namespace App\Controller\Security;

use App\Entity\RegistrationToken;
use App\Enum\RolesEnum;
use App\Form\Security\RegistrationTokenType;
use App\Repository\RegistrationTokenRepository;
use App\Util\Form\FormFlashHelper;
use App\Util\Security\RegistrationTokenHandler;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::ADMIN->value)]
#[Route('/registration-token', name: 'registration_token_')]
final class RegistrationTokenController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly FormFlashHelper             $formFlashHelper,
        private readonly RegistrationTokenHandler    $registrationTokenHandler,
        private readonly RegistrationTokenRepository $registrationTokenRepository,
    ) {}

    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $this->registrationTokenHandler->clearExpiredTokens();

        $form = $this->createForm(RegistrationTokenType::class, new RegistrationToken(), [
            'action' => $this->generateUrl('registration_token_new'),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        return $this->render('security/registration_token/index.html.twig', [
            'form' => $form->createView(),
            'registration_tokens' => $this->registrationTokenRepository->findAll()
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $registrationToken = new RegistrationToken();

        $form = $this->createForm(RegistrationTokenType::class, $registrationToken);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expiryTime = $form->get('expiryTime')->getData();
            $expiryDate = new DateTime();
            $expiryDate->add(new DateInterval('PT' . $expiryTime . 'S'));

            $registrationToken->setExpiryDate($expiryDate);
            $this->entityManager->persist($registrationToken);
            $this->entityManager->flush();

            $this->addFlash('success', "Un nouveau token d'enregistrement a été généré");

            return $this->redirectToRoute('registration_token_index');
        }

        /** @var FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors */
        $formErrors = $form->getErrors(true, false);
        $this->formFlashHelper->showFormErrorsAsFlash($formErrors);

        return $this->redirectToRoute('registration_token_index');
    }

    #[IsGranted(RolesEnum::ADMIN->value)]
    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, RegistrationToken $registrationToken): Response
    {
        if ($this->isCsrfTokenValid('delete' . $registrationToken->getId(), $request->getPayload()->get('_token'))) {
            $this->entityManager->remove($registrationToken);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "Le token a bien été supprimé"
            );
        }
        return $this->redirectToRoute('registration_token_index', [], Response::HTTP_SEE_OTHER);
    }
}
