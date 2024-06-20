<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:reset-password',
    description: 'Allows to change the password for the given user'
)]
final class ResetPasswordCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly UserRepository              $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    )
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->addArgument('username', InputArgument::REQUIRED, 'The username of the user');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $username = $input->getArgument('username');
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            $output->writeln('<fg=red>Error : User not found.' . PHP_EOL . 'Aborting... </>');
            return Command::FAILURE;
        }

        $question = new ConfirmationQuestion(
            "Are you sure that you want to reset the password for <fg=red>{$user->getUsername()}</>? (yes/no) <fg=yellow>[no]</>",
            false
        );
        if (!$helper->ask($input, $output, $question)) {
            $output->writeln('<fg=yellow>Aborting...</>');
            return Command::SUCCESS;
        }

        $passwordQuestion = new Question('Please enter the new password: ');
        $passwordQuestion->setHidden(true);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $helper->ask($input, $output, $passwordQuestion)
            )
        );

        $this->entityManager->flush();

        $output->writeln('<fg=green>SUCCESS : Password updated successfully.</>');
        return Command::SUCCESS;
    }
}
