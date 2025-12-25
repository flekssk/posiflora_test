<?php

declare(strict_types=1);

use App\Services\Orders\Actions\OrderCreateAction;
use App\Services\Shops\Actions\TelegramIntegrationCreateAction;
use App\Services\Shops\Actions\TelegramIntegrationStatusAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::prefix('shops')->group(function () {
        Route::post('{shopId}/telegram/connect', TelegramIntegrationCreateAction::class)
            ->name('shops.telegram.connect');
        Route::post('{shopId}/telegram/status', TelegramIntegrationStatusAction::class)
            ->name('shops.telegram.status');
        Route::prefix('orders')->group(function () {
            Route::post('', OrderCreateAction::class)->name('orders.create');
        });
    });
});
