<?php

declare(strict_types=1);

namespace Tests\Unit\ConversionStrategies;

use Modules\Order\ConversionStrategies\JpyToTwdConversionStrategy;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class JpyToTwdConversionStrategyTest extends TestCase
{
    #[DataProvider('conversionProvider')]
    public function testConvertJypToTwd(float $amountInUsd, float $expectedAmountInTwd): void
    {
        $strategy        = new JpyToTwdConversionStrategy();
        $convertedAmount = $strategy->convert($amountInUsd);
        self::assertSame($expectedAmountInTwd, $convertedAmount);
    }

    public static function conversionProvider(): iterable
    {
        return [
            '整數轉換'      => [1000.0, 250.0],
            '小數點轉換'     => [1000.55, 250.14], // 四捨五入到小數點後兩位
            '更精確的小數點轉換' => [1234.567, 308.64], // 四捨五入到小數點後兩位
            '小數點四捨五入'   => [1000.3333, 250.08], // 測試四捨五入
        ];
    }
}