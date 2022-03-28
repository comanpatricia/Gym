<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiTrainerLoginController
{
    public function index(): Response
    {
        /**
         * @Route("/api/trainer", methods={"GET"})
         */
        return new JsonResponse([
            'message' => 'This is an trainer controller'
        ]);
    }
}