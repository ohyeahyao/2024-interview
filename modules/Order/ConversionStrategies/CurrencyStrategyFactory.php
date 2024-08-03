<?php

declare(strict_types=1);

namespace Modules\Order\ConversionStrategies;

use Modules\Order\CurrencyConverterStrategyInterface;

class CurrencyStrategyFactory
{
    public function create(string $currency): CurrencyConverterStrategyInterface
    {
        switch ($currency) {
            case 'USD':
                return new UsdToTwdConversionStrategy();
            case 'JPY':
                return new JpyToTwdConversionStrategy();
        }

        throw new \InvalidArgumentException("Unsupported currency: {$currency}");
    }
}