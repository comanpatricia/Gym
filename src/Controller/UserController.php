<?php

namespace App\Controller;

use App\Controller\Dto\UserDto;
use App\Entity\User;
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
 * @Route(path="/api/users")
 */
class UserController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private ValidatorInterface $validator;

    private EntityManagerInterface $entityManager;

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * @Route(methods={"POST"})
     */
    public function register(UserDto $userDto): Response
    {
        $this->logger->info('An user is registered');

        $user = User::createFromDto($userDto);

        $plainPassword = $this->getPlainPassword($user, $this->plainPassword);

        $user->setPassword($this->userPasswordHasher->hashPassword($user, $this-$plainPassword));

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
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

        return new JsonResponse($savedDto, Response::HTTP_CREATED);
    }
}
