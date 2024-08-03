<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Shared\Rules;

use Modules\Shared\Rules\EachWordCapitalizedRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

#[CoversClass(EachWordCapitalizedRule::class)]
final class EachWordCapitalizedRuleTest extends TestCase
{
    public static function providerCorrectInput(): iterable
    {
        return [
            ['John'],
            ['John Yao'],
            ['JohnYao'],
            ['Hello World'],
            ['Hello World '],
            [' Hello World '],
        ];
    }

    #[DataProvider('providerCorrectInput')]
    public function testCorrectInput($value): void
    {
        $fail = function ($message): void
        {
            $this->fail("The validation should have passed, but it failed with message: $message");
        };
        
        $rule = new EachWordCapitalizedRule();
        $rule->validate('name', $value, $fail);
        self::assertTrue(true);
    }

    public static function provideInvalidInput(): iterable
    {
        return [
            ['john Doe'],
            ['Jane doe'],
            ['hello World'],
            ['john doe'],
        ];
    }

    #[DataProvider('provideInvalidInput')]
    public function testInvalidInput($value): void
    {
        $rule      = new EachWordCapitalizedRule();
        $attribute = 'name';

        $fail = function ($message) use ($attribute): void
        {
            $this->assertSame(ucfirst($attribute) . ' is not capitalized', $message);
        };

        $rule->validate($attribute, $value, $fail);
    }
}
