<?php

declare(strict_types=1);

namespace Modules\Order\Enums;

enum Currency: string
{
    case TWD = 'TWD';
    case USD = 'USD';
    case JPY = 'JPY';
}