<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderKitchenController;
use App\Http\Controllers\DishController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('api')->group(function () {

    Route::controller(OrderKitchenController::class)->group(function () {
        Route::get('/orders', 'index')->name('orders.index');
        Route::get('/order/{uuid}', 'show')->name('order.show');

        Route::post('/order/store', 'store');
    });

    Route::controller(DishController::class)->group(function () {
        Route::get('/dishes', 'index')->name('dishes.index');
    });

});
