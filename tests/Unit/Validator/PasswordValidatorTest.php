<?php

namespace App\Tests\Unit\Validator;

use App\Validator\Password;
use App\Validator\PasswordValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class PasswordValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): PasswordValidator
    {
        return new PasswordValidator();
    }

    /**
     * @dataProvider getValidPassword
     */
    public function testValidPassword(string $password)
    {
        $this->validator->validate($password, new Password());
        $this->assertNoViolation();
    }

    /**
     * @dataProvider getInvalidPassword
     */
    public function testInvalidCnp(string $password)
    {
        $this->validator->validate($password, new Password());
        $this->buildViolation('The password must contain at least 8 chars including an uppercase char & a special char')
             ->assertRaised();
    }

    public function getValidPassword(): array
    {
        return [
            ['Admin123'],
            ['ADMIN123'],
        ];
    }

    public function getInvalidPassword(): array
    {
        return [
//            ['aaaaaaaaaaa'],
//            ['AAAAAAAAAAA'],
            ['!!!!!!!!!!!!!!'],
//            ['11234567890'],
            [''],
            ['a'],
            ['aaa!2'],
            ['aaa!2aaaa'],
            ['_2222222222'],
            ['_22222'],
            ['_AAAAAAAAAAAA'],
        ];
    }
}
