<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Shared\Rules;

use Modules\Shared\Rules\ContainsNotEnglishCharactersRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

#[CoversClass(ContainsNotEnglishCharactersRule::class)]
final class ContainsNotEnglishCharactersRuleTest extends TestCase
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
        
        $rule = new ContainsNotEnglishCharactersRule();
        $rule->validate('name', $value, $fail);
        self::assertTrue(true);
    }

    public static function provideInvalidInputWithNonEnglishCharactersCases(): iterable
    {
        return [
            ['嗨 John'],
            ['John!'],
            ['JohnDoe123'],
            ['Hello 世界'],
        ];
    }

    #[DataProvider('provideInvalidInputWithNonEnglishCharactersCases')]
    public function testInvalidInputWithNonEnglishCharacters($value): void
    {
        $rule      = new ContainsNotEnglishCharactersRule();
        $attribute = 'name';

        $fail = function ($message) use ($attribute): void
        {
            $this->assertSame(ucfirst($attribute) . ' contains non-English characters', $message);
        };

        $rule->validate($attribute, $value, $fail);
    }
}
