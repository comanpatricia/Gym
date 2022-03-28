<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

/**
 * @Route("/api")
 */
class ApiLoginController extends AbstractController
{
    private UserRepository $userRepository;

    private Security $security;

    public function __construct(UserRepository $userRepository, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
    }

    /**
     * @Route("/login", name="api_login", methods={"POST"})
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (null === $user) {
            return new JsonResponse([
                'message' => 'Credentials not found.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = Uuid::v4();
        $user->setToken($token);
        $this->userRepository->add($user);

        return new JsonResponse([
            'user' => $user->getUserIdentifier(),
            'token' => $token
        ]);
    }
}
