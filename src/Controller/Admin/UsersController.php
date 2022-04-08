<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("admin/users", name="admin_users", methods={"GET"})
     */
    public function getAllUsers(Request $request): Response
    {
        $allUsers = $this->userRepository->findAll();

        return $this->render('Admin/allUsers.html.twig', ['allUsers' => $allUsers ]);
    }
}
