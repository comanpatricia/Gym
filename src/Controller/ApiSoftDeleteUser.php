<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiSoftDeleteUser extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private UserRepository $userRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(path="api/users/{id}", methods="DELETE")
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function softDeleteUser(int $id): Response
    {
        $userToDelete = $this->userRepository->findOneBy(['id' => $id]);

        if (null === $userToDelete) {
            return new Response('User does not exist', Response::HTTP_NOT_FOUND);
        }
        $this->userRepository->remove($userToDelete);
        $this->entityManager->flush();

        $this->logger->info('User soft-deleted');

        return new Response('User soft-deleted', Response::HTTP_OK);
    }
}
