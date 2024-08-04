<?php

declare(strict_types=1);

namespace Modules\Order;

interface CurrencyStrategyFactoryInterface
{
    public function create(string $currency): CurrencyConverterStrategyInterface;
}