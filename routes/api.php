<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CityController;
use App\Http\Controllers\API\DiscountController;
use App\Http\Controllers\API\PostActionsController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'API'], function(){

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::group(['middleware' => ['jwt.verify']], function (){
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
            Route::get('/city-discounts/{id}', [DiscountController::class, 'cityDiscounts']);
            Route::get('/', [DiscountController::class, 'index']);
        });

        Route::group(['prefix' => 'posts'], function () {
            Route::Post('/like', [PostActionsController::class, 'like']);
            Route::Post('/favorite', [PostActionsController::class, 'favorite']);
            Route::Post('/rate', [PostActionsController::class, 'ratePost']);
            Route::Post('/delete-rate', [PostActionsController::class, 'deleteRate']);
            Route::group(['prefix' => 'comments'], function () {
                Route::Post('/add', [PostActionsController::class, 'comment']);
                Route::Post('/replay', [PostActionsController::class, 'replayComment']);
                Route::Post('/delete', [PostActionsController::class, 'deleteComment']);
                Route::Post('/like', [PostActionsController::class, 'likeComment']);
                Route::get('/{id}', [PostActionsController::class, 'comments']);
            });
        });
    });
});
