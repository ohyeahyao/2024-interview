<?php

declare(strict_types=1);

namespace Tests\Unit\ConversionStrategies;

use Modules\Order\ConversionStrategies\JpyToTwdConversionStrategy;
use PHPUnit\Framework\TestCase;

class JpyToTwdConversionStrategyTest extends TestCase
{
    public function testConvertJypToTwd(): void
    {
        $strategy            = new JpyToTwdConversionStrategy();
        $amountInJyp         = 1000.0;
        $expectedAmountInTwd = 250.0;

        $convertedAmount = $strategy->convert($amountInJyp);

        self::assertSame($expectedAmountInTwd, $convertedAmount);
    }
}