<?php

declare(strict_types=1);

namespace Modules\Order\Exceptions;

use Exception;

class CurrencyConvertDataInvalidException extends Exception
{
    protected array $errors;

    public function __construct(string $message, array $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}