<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
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
    private string $plainPassword;

    private ValidatorInterface $validator;

    private EntityManager $entityManager;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        ValidatorInterface $validator,
        EntityManager $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;

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
        $roles = $input->getArgument('roles');

        $user = new User();

        $hashedPassword = $this->passwordHasher->hashPassword($user, $this->plainPassword);

        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->email = $email;
        $user->password = $hashedPassword;
        $user->setPlainPassword($this->plainPassword);
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
