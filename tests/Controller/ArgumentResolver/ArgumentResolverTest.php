<?php

namespace App\Tests\Controller\ArgumentResolver;

use App\Controller\ArgumentResolver\UserDtoArgumentValueResolver;
use App\Controller\Dto\UserDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ArgumentResolverTest extends TestCase
{
    private UserDtoArgumentValueResolver $userDtoArgumentValueResolver;

    protected function setUp(): void
    {
        $this->userDtoArgumentValueResolver = new UserDtoArgumentValueResolver();
    }

    public function testSupportsDtoClass(): void
    {
        $request = Request::create('/test');
        $argumentMetadata = new ArgumentMetadata('test', UserDto::class, true, true, true, false);
        $result = $this->userDtoArgumentValueResolver->supports($request, $argumentMetadata);

        self::assertTrue($result);
    }

    public function testResolveArgument()
    {
        $request = Request::create(
            '/test',
            'GET',
            [],
            [],
            [],
            [],
            json_encode(['firstName' => 'Patricia',
                        'lastName' => 'Coman',
                        'email' => 'eu@yahoo.com',
                        'cnp' => '2990505060019',
                        'password' => 'Parola123.',
                        'confirmPassword' => 'Parola123.',
                    ])
        );

        $argumentMetadata = new ArgumentMetadata('test', UserDto::class, true, true, true, false);
        $dto = null;
        foreach ($this->userDtoArgumentValueResolver->resolve($request, $argumentMetadata) as $result)
        {
            $dto = $result;
        }

        $userDto = new UserDto();
        $userDto->firstName = 'Patricia';
        $userDto->lastName = 'Coman';
        $userDto->email = 'eu@yahoo.com';
        $userDto->cnp = '2990505060019';
        $userDto->password = 'Parola123.';


        self::assertEquals($userDto, $dto);
    }
}

