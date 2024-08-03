<?php

declare(strict_types=1);

namespace Modules\Order\ConversionStrategies;

use Modules\Order\CurrencyConverterStrategyInterface;
use Modules\Order\Enums\Currency;

class CurrencyStrategyFactory
{
    public function create(string $currency): CurrencyConverterStrategyInterface
    {
        switch ($currency) {
            case Currency::USD->value:
                return new UsdToTwdConversionStrategy();
            case Currency::JPY->value:
                return new JpyToTwdConversionStrategy();
        }

        throw new \InvalidArgumentException("Unsupported currency: {$currency}");
    }
}