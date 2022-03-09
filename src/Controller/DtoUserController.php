<?php

namespace App\Controller;

use App\Controller\Dto\UserDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DtoUserController
{
    /**
     * @Route("\user", name="create_user", methods={"POST"})
     */
    public function doSomething(UserDto $userDto): Response
    {
//        $userDto->id = 1;
        return new JsonResponse($userDto);
    }
}

