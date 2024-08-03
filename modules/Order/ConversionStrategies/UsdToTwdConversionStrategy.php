<?php

declare(strict_types=1);

namespace Modules\Order\ConversionStrategies;

final class UsdToTwdConversionStrategy extends AbstractConversionStrategy
{
    protected function getExchangeRate(): float
    {
        return 31.0;
    }
}