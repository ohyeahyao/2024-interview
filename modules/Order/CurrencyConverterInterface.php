<?php

declare(strict_types=1);

namespace Modules\Order;

use Modules\Order\Entities\Order;

interface CurrencyConverterInterface
{
    public function convert(array $data): Order;
}