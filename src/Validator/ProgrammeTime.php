<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class ProgrammeTime extends Constraint
{
    public string $message = "This is not a valid time.";
}
