<?php

namespace App\Command;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(name: 'app:purge-old-logs')]
final class PurgeOldLogsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setDescription('Purges logs that are older than one month.');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = new DateTime();
        $date->modify('-1 month');

        $query = $this->entityManager->createQuery(
            'DELETE FROM App\Entity\Log l WHERE l.date < :date'
        )->setParameter('date', $date);

        $numDeleted = $query->execute();

        $output->writeln(sprintf('Deleted %d old log(s)', $numDeleted));

        return Command::SUCCESS;
    }
}
