<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Shared\Rules;

use InvalidArgumentException;
use Modules\Shared\Rules\AmountExceedsRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

#[CoversClass(AmountExceedsRule::class)]
final class AmountExceedsRuleTest extends TestCase
{
    public static function validInputProvider():iterable
    {
        return [
            [2500, 2000],
            [3000, 2500],
            [5000, 4500],
        ];
    }

    #[DataProvider('validInputProvider')]
    public function testValidInput($limit, $value): void
    {
        $fail = function ($message): void
        {
            $this->fail("The validation should have passed, but it failed with message: $message");
        };
        
        $rule = new AmountExceedsRule($limit);
        $rule->validate('price', $value, $fail);
        self::assertTrue(true);
    }

    public static function invalidInputProvider():iterable
    {
        return [
            [1500, 2000],
            [2000, 2500],
            [4000, 4500],
        ];
    }
    
    #[DataProvider('invalidInputProvider')]
    public function testInvalidInput($limit, $value): void
    {
        $rule = new AmountExceedsRule($limit);

        $fail = function ($message) use ($limit): void
        {
            $this->assertSame("Price is over $limit", $message);
        };

        $rule->validate('price', $value, $fail);
    }

    public static function invalidLimitProvider():iterable
    {
        return [
            ['two thousand'],
            [null],
            [false],
            [[]],
        ];
    }

    #[DataProvider('invalidLimitProvider')]
    public function testInvalidLimit($limit): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be a numeric value.');

        new AmountExceedsRule($limit);
    }
}
