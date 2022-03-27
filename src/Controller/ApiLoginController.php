<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Uuid;

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
        $user = $this->security->getUser();

        if (null === $user) {
            return new JsonResponse([
                'message' => 'Credentials not found.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $apiToken = Uuid::v4();
        $user->setApiToken($apiToken);
        $this->userRepository->add($user);

        return new JsonResponse([
            'user' => $user->getUserIdentifier(),
            'token' => $apiToken
        ]);

//        return $this->json([
//            'message' => 'Welcome to api controller!',
//            'path' => 'src/Controller/ApiLoginController.php',
//        ]);
    }
}
