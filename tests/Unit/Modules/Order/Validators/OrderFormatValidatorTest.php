<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;
use Modules\Order\Entities\Order;
use Modules\Order\Validators\OrderFormatValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

#[CoversClass(OrderFormatValidator::class)]
final class OrderFormatValidatorTest extends TestCase
{
    private function createMockValidationRule(string $message): MockObject
    {
        $mockRule = $this->createMock(ValidationRule::class);
        $mockRule->method('validate')
                 ->willReturnCallback(static function (string $attribute, mixed $value, \Closure $fail) use ($message): void
                 {
                     $fail($message);
                 });

        return $mockRule;
    }
    
    public function testExecReturnsCorrectErrors(): void
    {
        $mockValidCurrencyRule                = $this->createMockValidationRule('Currency format is wrong');
        $mockAmountExceedsRule                = $this->createMockValidationRule('The price must exceed ' . OrderFormatValidator::PRICE_LIMIT);
        $mockContainsNotEnglishCharactersRule = $this->createMockValidationRule('The name contains non-English characters');
        $mockEachWordCapitalizedRule          = $this->createMockValidationRule('Each word in name must be capitalized');

        $validationRules = [
            'currency' => [$mockValidCurrencyRule],
            'price'    => [$mockAmountExceedsRule],
            'name'     => [$mockContainsNotEnglishCharactersRule, $mockEachWordCapitalizedRule],
        ];

        $validator = new OrderFormatValidator($validationRules);

        $mockOrder = $this->createMock(Order::class);
        $mockOrder->expects(self::once())->method('getCurrency');
        $mockOrder->expects(self::once())->method('getPrice');
        $mockOrder->expects(self::exactly(2))->method('getName');

        $errors = $validator->exec($mockOrder);

        $expectedErrors = [
            'currency' => ['Currency format is wrong'],
            'price'    => ['The price must exceed 2000'],
            'name'     => ['The name contains non-English characters', 'Each word in name must be capitalized'],
        ];
        self::assertSame($expectedErrors, $errors);
    }

    public function testThrowExceptionWhenValidatedOtherAttribute(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $mockValidAddressRule = $this->createMockValidationRule('Address format is wrong');

        $validationRules = [
            'address' => [$mockValidAddressRule],
        ];

        $validator = new OrderFormatValidator($validationRules);

        $mockOrder = $this->createMock(Order::class);
        $mockOrder->expects(self::any())->method('getCurrency');
        $mockOrder->expects(self::any())->method('getPrice');
        $mockOrder->expects(self::any())->method('getName');
        $mockOrder->expects(self::any())->method('getAddress');

        $validator->exec($mockOrder);
    }
}
