<?php

declare(strict_types=1);

namespace Modules\Order\ConversionStrategies;

use Modules\Order\CurrencyConverterStrategyInterface;

abstract class AbstractConversionStrategy implements CurrencyConverterStrategyInterface
{
    abstract protected function getExchangeRate(): float;

    public function convert(float $amount): float
    {
        return round($amount * $this->getExchangeRate(), 2);
    }
}