<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin-user',
    description: 'Creates an admin user',
)]
class CreateAdminUserCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $hasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The email for the admin')
            ->addArgument('password', InputArgument::REQUIRED, 'The password for the admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $admin = new User();
        $admin->setEmail($email);
        $admin->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $hashedPassword = $this->hasher->hashPassword($admin, $password);
        $admin->setPassword($hashedPassword);

        try {
            $this->userRepository->save($admin);
        } catch (UniqueConstraintViolationException) {
            $io->error('The user already exists');

            return Command::FAILURE;
        }

        $io->success("The admin $email is created");

        return Command::SUCCESS;
    }
}
