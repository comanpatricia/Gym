<?php

namespace App\Command\Exceptions;

use Throwable;

class InvalidCsvRowException extends \Exception
{
    private array $csvRow;

    public function __construct($message = "", $code = 0, Throwable $previous = null, array $csvRow)
    {
        $this->csvRow = $csvRow;

        parent::__construct($message, $code, $previous);
    }

    public function getCsvRow(): array
    {
        return $this->csvRow;
    }
}
