<?php

declare(strict_types=1);

namespace Tests\Unit\ConversionStrategies;

use Modules\Order\ConversionStrategies\UsdToTwdConversionStrategy;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UsdToTwdConversionStrategyTest extends TestCase
{
    #[DataProvider('conversionProvider')]
    public function testConvertUsdToTwd(float $amountInUsd, float $expectedAmountInTwd): void
    {
        $strategy        = new UsdToTwdConversionStrategy();
        $convertedAmount = $strategy->convert($amountInUsd);
        self::assertSame($expectedAmountInTwd, $convertedAmount);
    }

    public static function conversionProvider(): iterable
    {
        return [
            '整數轉換'      => [1000.0, 31000.0],
            '小數點轉換'     => [1000.55, 31017.05], // 四捨五入到小數點後兩位
            '更精確的小數點轉換' => [1234.567, 38271.58], // 1234.567 * 31 = 38271.577 四捨五入到小數點後兩位
            '小數點四捨五入'   => [1000.3333, 31010.33], // 1000.3333*31 = 31010.3323 測試四捨五入 
        ];
    }
}