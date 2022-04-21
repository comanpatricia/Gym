<?php

namespace App\Tests\Unit\Validator;

use App\Validator\Cnp;
use App\Validator\CnpValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class CnpValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): CnpValidator
    {
        return new CnpValidator();
    }

    /**
     * @dataProvider getValidCnp
     */
    public function testValidCnp(string $cnp)
    {
        $this->validator->validate($cnp, new Cnp());
        $this->assertNoViolation();
    }

    /**
     * @dataProvider getInvalidCnp
     */
    public function testInvalidCnp(string $cnp)
    {
        $this->validator->validate($cnp, new Cnp());
        $this->buildViolation('This is not a valid CNP.')
             ->assertRaised();
    }

    public function getValidCnp(): array
    {
        return [
            ['2990505060019'],
        ];
    }

    public function getInvalidCnp(): array
    {
        return [
            ['a299050506001'],
            ['A299050506001'],
            ['_299050506001'],
            ['/299050506001'],
            ['/29901'],
            ['29901'],
            ['a29901'],
        ];
    }
}
