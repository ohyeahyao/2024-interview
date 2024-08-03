<?php

declare(strict_types=1);

namespace Modules\Order\ConversionStrategies;

use Modules\Order\CurrencyConverterStrategyInterface;

final class JpyToTwdConversionStrategy extends AbstractConversionStrategy implements CurrencyConverterStrategyInterface
{
    protected function getExchangeRate(): float
    {
        return 0.25;
    }
}