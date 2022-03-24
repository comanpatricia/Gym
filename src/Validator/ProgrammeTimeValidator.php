<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ProgrammeTimeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ProgrammeTime) {
            throw new UnexpectedTypeException($constraint, ProgrammeTime::class);
        }

        $regex = "/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})$/";
        $regexResponse = preg_match_all($regex, $value, $matches, PREG_SET_ORDER);

        if ($regexResponse) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
