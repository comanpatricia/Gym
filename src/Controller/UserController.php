<?php

namespace App\Controller;

use App\Controller\Dto\UserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(path="/api/user")
 */
class UserController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private ValidatorInterface $validator;

    private EntityManagerInterface $entityManager;

    private UserPasswordHasherInterface $userPasswordHasher;

    private UserRepository $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $userPasswordHasher,
        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route(path="/register", name="api_user_register", methods={"POST"})
     */
    public function register(UserDto $userDto): Response
    {
        $this->logger->info('An user is registered');

        $user = User::createFromDto($userDto);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPlainPassword()));

        $errors = $this->validator->validate($user);
        if (\count($errors) > 0) {
            $errorArray = [];
            foreach ($errors as $error) {

                /** @var ConstraintViolation $error */
                $errorArray[$error->getPropertyPath()] = $error->getMessage();
            }

            return new JsonResponse($errorArray);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->entityManager->refresh($user);
        $savedDto = UserDto::createFromUser($user);

        $this->logger->info('User registered successfully!');

        return new JsonResponse($savedDto, Response::HTTP_CREATED);
    }

    /**
     * @Route(path="/{id}",  name="api_user_delete", methods="DELETE")
     */
    public function softDeleteUser(int $id): Response
    {
        $userToDelete = $this->userRepository->findOneBy(['id' => $id]);

        if (null === $userToDelete) {
            return new Response('User does not exist', Response::HTTP_NOT_FOUND);
        }
        $this->entityManager->remove($userToDelete);
        $this->entityManager->flush();

        $this->logger->info('An user was deleted');

        return new Response('User was deleted successfully', Response::HTTP_OK);
    }

    /**
     * @Route(path="/recover/{email}", name="api_user_recover_account", methods="POST")
     */
    public function recoverAccount(string $email): Response
    {
        $filters = $this->entityManager->getFilters();
        $filters->disable('softdeleteable');

        $accountToRecover = $this->userRepository->findOneBy(['email' => $email]);
        if (null === $accountToRecover) {
            return new Response('No such account', Response::HTTP_NOT_FOUND);
        }

        $accountToRecover->setDeletedAt(null);
        $this->entityManager->persist($accountToRecover);
        $this->entityManager->flush();

        $this->logger->info('An user account was recovered');

        return new Response('User account recovered successfully', Response::HTTP_OK);
    }
}
