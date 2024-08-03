<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CheckAndTransformOrderRequest;
use Illuminate\Http\JsonResponse;

class CheckAndTransformOrderController extends Controller
{
    public function main(CheckAndTransformOrderRequest $request): JsonResponse
    {
        return response()->json($request->all());
    }
}
