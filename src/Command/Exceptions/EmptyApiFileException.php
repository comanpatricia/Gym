<?php

namespace App\Command\Exceptions;

use Throwable;

class EmptyApiFileException extends \Exception
{
    private array $data;

    public function __construct($message = "", $code = 0, Throwable $previous = null, string $data)
    {
        $this->data = $data;

        parent::__construct($message, $code, $previous);
    }

    public function getFilePath(): string
    {
        return $this->data;
    }
}
