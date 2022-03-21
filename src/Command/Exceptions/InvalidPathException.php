<?php

namespace App\Command\Exceptions;

use Throwable;

class InvalidPathException extends \Exception
{
    private string $filePath;

    public function __construct($message = "", $code = 0, Throwable $previous = null, string $filePath)
    {
        $this->filePath = $filePath;

        parent::__construct($message, $code, $previous);
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }
}
