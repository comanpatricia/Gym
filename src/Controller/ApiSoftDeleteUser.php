<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

//    /**
//     * @Route(path="api/users/{id}", methods="DELETE")
//     * @throws ORMException
//     * @throws OptimisticLockException
//     */
//    public function softDeleteUser(int $id): Response
//    {
//        $userToDelete = $this->userRepository->findOneBy(['id' => $id]);
//
//        if (null === $userToDelete) {
//            return new Response('User does not exist', Response::HTTP_NOT_FOUND);
//        }
//        $this->userRepository->remove($userToDelete);
//        $this->entityManager->flush();
//
//        $this->logger->info('An user was soft-deleted');
//
//        return new Response('User soft-deleted successfully', Response::HTTP_OK);
//    }

//    /**
//     * @Route(path="api/users/recover/{id}", methods="POST")
//     * @throws ORMException
//     * @throws OptimisticLockException
//     */
//    public function recoverAccount(int $id): Response
//    {
//
//        $accountToRecover = $this->userRepository->findOneBy(['id' => $id]);
//        $filters = $this->entityManager->getFilters();
//        $filters->disable('softdeleteable');
//
//        if (null === $accountToRecover) {
//            return new Response('Account does not exist in our database', Response::HTTP_NOT_FOUND);
//        }
//
//        $this->entityManager->flush();
//
//        $this->logger->info('An user account was recovered');
//
//        return new Response('User account recovered successfully', Response::HTTP_OK);
//    }
}
