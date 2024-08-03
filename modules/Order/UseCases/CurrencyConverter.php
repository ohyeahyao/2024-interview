<?php

declare(strict_types=1);
namespace Modules\Order\UseCases;

use Modules\Order\CurrencyConverterInterface;
use Modules\Order\Entities\Order;

final class CurrencyConverter implements CurrencyConverterInterface
{
    public function convert(array $data): Order
    {
        return Order::fromArray($data);
    }
}
