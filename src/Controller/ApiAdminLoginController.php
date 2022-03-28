<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiAdminLoginController
{
    public function index(): Response
    {
        /**
         * @Route("/api/admin", methods={"GET"})
         */
        return new JsonResponse([
            'message' => 'This is an admin controller'
        ]);
    }
}
