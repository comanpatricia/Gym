<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Password){
            throw new UnexpectedTypeException($constraint, Password::class);
        }

        $regex = "/^(?=.*[A-Z])(?=.*[!@#$%^&*])[\w!@#$%^&*]{8,}$/m";
        $regexResponse = preg_match_all($regex, $value, $matches, PREG_SET_ORDER, 0);

        if ($regexResponse) {
            return;
        }

        $this->context->buildViolation($constraint->message)
             ->addViolation();
    }
}

