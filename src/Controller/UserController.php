<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/user")
 */
class UserController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface  $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(methods={"POST"})
     */
    public function register(Request $request): Response
    {
        $data = $request->getContent();
        $decodedData = json_decode($data,true);

        $newUser = new User();
        $newUser->cnp = $decodedData['cnp'];
        $newUser->firstName = $decodedData['firstName'];
        $newUser->lastName = $decodedData['lastName'];
        $newUser->email = $decodedData['email'];
        $newUser->password = $decodedData['password'];
        $newUser->setRoles(['customer']);

        $this->entityManager->persist($newUser);
        $this->entityManager->flush();

        return new JsonResponse($newUser, Response::HTTP_CREATED);
    }
}
