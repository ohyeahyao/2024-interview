<?php

declare(strict_types=1);

namespace Tests\Unit\ConversionStrategies;

use Modules\Order\ConversionStrategies\UsdToTwdConversionStrategy;
use PHPUnit\Framework\TestCase;

class UsdToTwdConversionStrategyTest extends TestCase
{
    public function testConvertUsdToTwd(): void
    {
        $amountInUsd         = 1000.0;
        $expectedAmountInTwd = 31000.0;
        $strategy            = new UsdToTwdConversionStrategy();
        $convertedAmount     = $strategy->convert($amountInUsd);
        self::assertSame($expectedAmountInTwd, $convertedAmount);
    }
}