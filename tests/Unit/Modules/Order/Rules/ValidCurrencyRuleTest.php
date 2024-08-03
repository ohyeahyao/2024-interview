<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Order\Rules;

use Modules\Order\Rules\ValidCurrencyRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

#[CoversClass(ValidCurrencyRule::class)]
final class ValidCurrencyRuleTest extends TestCase
{
    public static function provideInvalidInput(): iterable
    {
        return [
            ['USD'],
            ['TWD'],
        ];
    }

    #[DataProvider('provideInvalidInput')]
    public function testValidInput($value): void
    {
        $fail = function ($message): void
        {
            $this->fail("The validation should have passed, but it failed with message: $message");
        };
        
        $rule = new ValidCurrencyRule();
        $rule->validate('currency', $value, $fail);
        self::assertTrue(true);
    }

    public static function invalidInputProvider(): iterable
    {
        return [
            ['USd'],
            ['TwD'],
            ['AUD'],
            ['JPY'],
        ];
    }
    #[DataProvider('invalidInputProvider')]
    public function testInvalidInput($value): void
    {
        $fail = function ($message): void
        {
            $this->assertSame('Currency format is wrong', $message);
        };
        $rule = new ValidCurrencyRule();
        $rule->validate('currency', $value, $fail);
        self::assertTrue(true);
    }
}
