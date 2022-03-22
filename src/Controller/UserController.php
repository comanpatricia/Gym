<?php

namespace App\Controller;

use App\Controller\Dto\UserDto;
use App\Entity\User;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
    private RoomRepository $roomRepository;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, RoomRepository $roomRepository)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->roomRepository = $roomRepository;
    }

    /**
     * @Route(methods={"POST"})
     */
    public function register(UserDto $userDto): Response
    {
        $this->logger->info('An user is registered');

        $user = User::createFromDto($userDto);

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

        $this->roomRepository->getAllRooms();

        return new JsonResponse($savedDto, Response::HTTP_CREATED);
    }
}
