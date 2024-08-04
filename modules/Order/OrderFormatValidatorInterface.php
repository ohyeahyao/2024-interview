<?php

declare(strict_types=1);

namespace Modules\Order;

use Modules\Order\Entities\Order;

interface OrderFormatValidatorInterface
{
    public function exec(Order $order): array;
}