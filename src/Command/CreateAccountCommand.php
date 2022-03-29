<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateAccountCommand extends Command
{
    protected static $defaultName = 'app:create-account';

    protected static $defaultDescription = 'Creates a new account.';

    private UserPasswordHasherInterface $userPasswordHasher;

    private string $plainPassword;

    private EntityManagerInterface $entityManager;

    private ValidatorInterface $validator;

    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('firstName', InputArgument::REQUIRED, 'Enter your first name');
        $this->addArgument('lastName', InputArgument::REQUIRED, 'Enter your last name');
        $this->addArgument('email', InputArgument::REQUIRED, 'Enter your email');
        $this->addArgument('cnp', InputArgument::REQUIRED, 'Enter your CNP');
        $this->addOption(
            'role',
            null,
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'User\'s role',
            ['ROLE_ADMIN']
        );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('Enter the password');
        $question->setHidden(true);
        $this->plainPassword = $helper->ask($input, $output, $question);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);

        $firstName = $input->getArgument('firstName');
        $lastName = $input->getArgument('lastName');
        $email = $input->getArgument('email');
        $cnp = $input->getArgument('cnp');
        $roles = $input->getOption('role');

        $user = new User();
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->email = $email;
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $this->plainPassword));
        $user->setRoles($roles);
        $user->cnp = $cnp;

        $violationList = $this->validator->validate($user);
        if ($violationList->count() > 0) {
            foreach ($violationList as $violation) {
                $inputOutput->error($violation);
            }

            return self::FAILURE;
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $inputOutput->success('A new user was created');

        return self::SUCCESS;
    }
}
