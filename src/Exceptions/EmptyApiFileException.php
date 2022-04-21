<?php

namespace App\Exceptions;

class EmptyApiFileException extends \Exception
{
    public function showMessage(): string
    {
        return 'This file contains nothing';
    }
}
