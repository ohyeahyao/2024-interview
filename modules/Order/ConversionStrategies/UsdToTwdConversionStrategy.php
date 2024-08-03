<?php

declare(strict_types=1);

namespace Modules\Order\ConversionStrategies;

use Modules\Order\CurrencyConverterStrategyInterface;

final class UsdToTwdConversionStrategy implements CurrencyConverterStrategyInterface
{
    private const EXCHANGE_RATE = 31.0;

    public function convert(float $amount): float
    {
        return round($amount * self::EXCHANGE_RATE, 2);
    }
}