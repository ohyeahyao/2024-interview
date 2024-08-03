<?php

declare(strict_types=1);

namespace Tests\Unit\ConversionStrategies;

use Modules\Order\ConversionStrategies\CurrencyStrategyFactory;
use Modules\Order\ConversionStrategies\JpyToTwdConversionStrategy;
use Modules\Order\ConversionStrategies\UsdToTwdConversionStrategy;
use Modules\Order\CurrencyConverterStrategyInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CurrencyStrategyFactory::class)]
class CurrencyStrategyFactoryTest extends TestCase
{
    public function testCreateUsdToTwdStrategy(): void
    {
        $factory  = new CurrencyStrategyFactory();
        $strategy = $factory->create('USD');
        self::assertInstanceOf(UsdToTwdConversionStrategy::class, $strategy);
        self::assertInstanceOf(CurrencyConverterStrategyInterface::class, $strategy);
    }

    public function testCreateJypToTwdStrategy(): void
    {
        $factory  = new CurrencyStrategyFactory();
        $strategy = $factory->create('JPY');
        self::assertInstanceOf(JpyToTwdConversionStrategy::class, $strategy);
        self::assertInstanceOf(CurrencyConverterStrategyInterface::class, $strategy);
    }

    public function testCreateUnsupportedCurrency(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported currency: EUR');

        $factory = new CurrencyStrategyFactory();
        $factory->create('EUR');
    }
}