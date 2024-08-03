<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CheckAndTransformOrderRequest;
use Illuminate\Http\JsonResponse;
use Modules\Order\CurrencyConverterInterface;
use Modules\Order\Entities\Order;

class CheckAndTransformOrderController extends Controller
{
    private CurrencyConverterInterface $currency_converter;
    
    public function __construct(CurrencyConverterInterface $currency_converter)
    {
        $this->currency_converter = $currency_converter;
    }

    public function main(CheckAndTransformOrderRequest $request): JsonResponse
    {
        /**
         * @var Order $order
         */
        $order = $this->currency_converter->convert($request->all());
        
        return response()->json($order->toArray());
    }
}
