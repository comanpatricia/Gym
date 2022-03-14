<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Password extends Constraint
{
    public $message = "The password must contain at least 8 characters including an uppercase character & a special character";
}
