<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiTrainerLoginController extends AbstractController
{
    /**
     * @Route("/api/trainer", name="api_trianer", methods={"GET"})
     */
    public function index(): Response
    {
        return new JsonResponse([
            'message' => 'This is a trainer controller'
        ]);
    }
}