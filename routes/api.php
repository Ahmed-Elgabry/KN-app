<?php

use App\Http\Controllers\API\AdPlanController;
use App\Http\Controllers\API\WalletRequestController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CityController;
use App\Http\Controllers\API\CraftController;
use App\Http\Controllers\API\DiscountController;
use App\Http\Controllers\API\GiftsController;
use App\Http\Controllers\API\PostActionsController;
use App\Http\Controllers\API\WalletController;
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

        Route::group(['prefix' => 'gifts'], function () {
            Route::Post('/store', [GiftsController::class, 'store']);
            Route::get('/edit/{id}', [GiftsController::class, 'edit']);
            Route::Post('/update', [GiftsController::class, 'update']);
            Route::Post('/delete', [GiftsController::class, 'delete']);
            Route::get('/', [GiftsController::class, 'index']);
        });

        Route::group(['prefix' => 'crafts'], function () {
            Route::Post('/store', [CraftController::class, 'store']);
            Route::get('/edit/{id}', [CraftController::class, 'edit']);
            Route::Post('/update', [CraftController::class, 'update']);
            Route::Post('/delete', [CraftController::class, 'delete']);
            Route::get('/', [CraftController::class, 'index']);
        });

        Route::group(['prefix' => 'ad-plans'], function () {
            Route::Post('/store', [AdPlanController::class, 'store']);
            Route::get('/edit/{id}', [AdPlanController::class, 'edit']);
            Route::Post('/update', [AdPlanController::class, 'update']);
            Route::Post('/delete', [AdPlanController::class, 'delete']);
            Route::get('/', [AdPlanController::class, 'index']);
        });

        Route::group(['prefix' => 'wallets'], function () {
            Route::Post('/increase-wallet', [WalletController::class, 'increaseWallet']);
            Route::Post('/decrease-wallet', [WalletController::class, 'decreaseWallet']);
            Route::Post('/create-wallet', [WalletController::class, 'createWallet']);
        });

        Route::group(['prefix' => 'wallet-request'], function () {
            Route::Post('/store', [WalletRequestController::class, 'store']);
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
