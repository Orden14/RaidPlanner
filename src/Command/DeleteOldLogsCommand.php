<?php

namespace App\Command;

use App\Entity\UserActivityLog;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:delete-old-logs',
    description: 'Delete logs older than 30 days'
)]
class DeleteOldLogsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    final protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = (new DateTime())->modify('-30 days');

        $this->entityManager->createQueryBuilder()
            ->delete(UserActivityLog::class, 'log')
            ->where('log.createdAt < :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->execute();

        $output->writeln('Old logs deleted.');

        return Command::SUCCESS;
    }
}
