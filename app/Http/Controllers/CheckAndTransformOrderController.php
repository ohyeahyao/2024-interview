<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckAndTransformOrderController extends Controller
{
    public function main(Request $request): JsonResponse
    {
        return response()->json($request->all());
    }
}
