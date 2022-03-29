<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiAdminLoginController extends AbstractController
{
    /**
     * @Route("/api/admin", name="api_admin", methods={"GET"})
     */
    public function index(): Response
    {
        return new JsonResponse([
            'message' => 'This is an admin controller'
        ]);
    }
}
