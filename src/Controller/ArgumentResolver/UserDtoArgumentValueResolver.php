<?php

namespace App\Controller\ArgumentResolver;

use App\Controller\Dto\UserDto;
use Doctrine\DBAL\Driver\API\SQLite\UserDefinedFunctions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UserDtoArgumentValueResolver implements ArgumentValueResolverInterface
{

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->getType() === UserDto::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $data = $request->getContent();
        $decodedData = json_decode($data, true);
        $userDto = new UserDto();
        $userDto->lastName = $decodedData['lastName'];
        $userDto->firstName = $decodedData['firstName'];
        $userDto->email = $decodedData['email'];
        $userDto->cnp = $decodedData['cnp'];
        $userDto->password = $decodedData['password'];

        yield $userDto;
    }
}