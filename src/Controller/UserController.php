<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    /**
     * @Route(path="/users", methods={"GET"})
     */
    public function getAllAction(Request $request): Response
    {
        return new Response('Hello Im Patricia', Response::HTTP_OK, []);
    }
}
