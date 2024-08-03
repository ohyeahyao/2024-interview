<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use Modules\Order\Exceptions\CurrencyConvertDataInvalidException;
use PHPUnit\Framework\TestCase;

class CurrencyConvertDataInvalidExceptionTest extends TestCase
{
    public function testExceptionMessageAndErrors(): void
    {
        $message = 'Validation failed';
        $errors  = [
            'currency' => ['Currency format is wrong'],
            'price'    => ['The price must exceed 2000'],
            'name'     => ['The name contains non-English characters', 'Each word in name must be capitalized'],
        ];

        $exception = new CurrencyConvertDataInvalidException($message, $errors);

        self::assertSame($message, $exception->getMessage());
        self::assertSame($errors, $exception->getErrors());
    }
}