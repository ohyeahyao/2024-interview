<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CheckAndTransformOrderRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Modules\Order\CurrencyConverterInterface;
use Modules\Order\Exceptions\CurrencyConvertDataInvalidException;

class CheckAndTransformOrderController extends Controller
{
    private CurrencyConverterInterface $currency_converter;
    
    public function __construct(CurrencyConverterInterface $currency_converter)
    {
        $this->currency_converter = $currency_converter;
    }

    public function main(CheckAndTransformOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->currency_converter->convert($request->toArray());

            return response()->json($order->toArray());
        } catch (CurrencyConvertDataInvalidException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors'  => $e->getErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
