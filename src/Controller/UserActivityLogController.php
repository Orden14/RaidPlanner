<?php

namespace App\Controller;

use App\Enum\RolesEnum;
use App\Enum\UserActivityLogTypeEnum;
use App\Repository\UserActivityLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RolesEnum::ADMIN->value)]
#[Route('/user-activity-log', name: 'user_activity_log_')]
final class UserActivityLogController extends AbstractController
{
    public function __construct(
        private readonly UserActivityLogRepository $userActivityLogRepository
    ) {}

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('user_activity_log/index.html.twig', [
            'logs' => $this->userActivityLogRepository->findLatestLogs(),
            'log_types' => UserActivityLogTypeEnum::toArray(),
        ]);
    }
}
