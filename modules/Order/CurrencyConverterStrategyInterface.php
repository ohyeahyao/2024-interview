<?php

declare(strict_types=1);

namespace Modules\Order;

interface CurrencyConverterStrategyInterface
{
    public function convert(float $amount): float;
}