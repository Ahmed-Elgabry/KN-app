<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'API'], function(){

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::group(['prefix' => 'cities'], function () {
        Route::Post('/add', [CityController::class, 'store']);
        Route::Post('/edit', [CityController::class, 'update']);
        Route::Post('/delete', [CityController::class, 'delete']);
        Route::get('/', [CityController::class, 'index']);
    });


    Route::group(['middleware' => ['verify.token']], function (){
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);

    });
});
