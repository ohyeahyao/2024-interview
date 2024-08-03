<?php

declare(strict_types=1);

namespace Modules\Order\ConversionStrategies;

use Modules\Order\CurrencyConverterStrategyInterface;

final class JpyToTwdConversionStrategy implements CurrencyConverterStrategyInterface
{
    private const EXCHANGE_RATE = 0.25;

    public function convert(float $amount): float
    {
        return round($amount * self::EXCHANGE_RATE, 2);
    }
}