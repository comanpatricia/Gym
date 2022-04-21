<?php

namespace App\Tests\Integration\Controller;

use App\Controller\Dto\UserDto;
use App\Controller\UserController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserControllerTest extends KernelTestCase
{
    protected function runTest(): void
    {
        $this->markTestSkipped('Skipped test');
    }

    private ?UserController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();

        $this->controller = $container->get(UserController::class);
    }

    public function testRegisterAccount(): void
    {
        $userDto = new UserDto();
        $userDto->email = 'patri@example.com';
        $userDto->firstName = 'Patricia';
        $userDto->lastName = 'Coman';
        $userDto->password = 'Patricia1';
        $userDto->confirmPassword = 'Patricia1';
        $userDto->cnp = '2830420175843';

        $this->controller->register($userDto);

        self::assertTrue(true);
    }

    public function testRegisterPasswordMissingEmail(): void
    {
        self::expectError();
        self::expectExceptionMessage(
            'Typed property App\Controller\Dto\UserDto::$email must not be accessed before initialization'
        );

        $userDto = new UserDto();
        $userDto->firstName = 'Patricia';
        $userDto->lastName = 'Coman';
        $userDto->password = 'Patricia1';
        $userDto->confirmPassword = 'Patricia1';
        $userDto->cnp = '2830420175843';

        $this->controller->register($userDto);
    }

    public function testRegisterPasswordMissingPassword(): void
    {
        self::expectError();
        self::expectExceptionMessage(
            'Typed property App\Controller\Dto\UserDto::$password must not be accessed before initialization'
        );

        $userDto = new UserDto();
        $userDto->email = 'patri@example.com';
        $userDto->firstName = 'Patricia';
        $userDto->lastName = 'Coman';
        $userDto->confirmPassword = 'Patricia1';
        $userDto->cnp = '2830420175843';

        $this->controller->register($userDto);
    }

    public function testRegisterAccountMissingCnp(): void
    {
        self::expectError();
        self::expectExceptionMessage(
            'Typed property App\Controller\Dto\UserDto::$cnp must not be accessed before initialization'
        );

        $userDto = new UserDto();
        $userDto->email = 'patri@example.com';
        $userDto->firstName = 'Patricia';
        $userDto->lastName = 'Coman';
        $userDto->password = 'Patricia1';
        $userDto->confirmPassword = 'Patricia1';

        $this->controller->register($userDto);
    }

    public function testRegisterNullUser(): void
    {
        self::expectError();
        self::expectExceptionMessage(
            'Typed property App\Controller\Dto\UserDto::$firstName must not be accessed before initialization'
        );

        $userDto = new UserDto();

        $this->controller->register($userDto);
    }
}
