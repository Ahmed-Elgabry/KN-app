<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CityController;
use App\Http\Controllers\API\DiscountController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'API'], function(){

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::group(['middleware' => ['auth:api']], function (){
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);

        Route::group(['prefix' => 'cities'], function () {
            Route::Post('/edit', [CityController::class, 'update']);
            Route::Post('/delete', [CityController::class, 'delete']);
            Route::Post('/add', [CityController::class, 'store']);
            Route::get('/', [CityController::class, 'index']);
        });

        Route::group(['prefix' => 'discounts'], function () {
            Route::Post('/store', [DiscountController::class, 'store']);
            Route::get('/edit/{id}', [DiscountController::class, 'edit']);
            Route::Post('/update', [DiscountController::class, 'update']);
            Route::Post('/delete', [DiscountController::class, 'delete']);
            Route::get('/user-discounts', [DiscountController::class, 'userDiscounts']);
            Route::get('/', [DiscountController::class, 'index']);
        });
    });
});
