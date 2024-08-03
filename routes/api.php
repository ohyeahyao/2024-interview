<?php

declare(strict_types=1);

use App\Constants\RouteNames;
use App\Http\Controllers\CheckAndTransformOrderController;
use Illuminate\Support\Facades\Route;

Route::name(RouteNames::ORDER_CHECK_AND_TRANSFORM)
->post(
    '/orders',
    [CheckAndTransformOrderController::class, 'main']
);

