<?php

use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\User\UserResourcesController;
use App\Http\Controllers\Api\User\UserStatsController;
use App\Http\Controllers\Api\User\UserWalletController;
use App\Http\Controllers\AuthController;
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
Route::middleware('auth:api')->prefix('users/{user}')->group(function () {
    Route::get(null, [UserController::class, 'show']);
    Route::get('stats', [UserStatsController::class, 'show']);
    Route::get('wallet', [UserWalletController::class, 'show']);
    Route::get('resources', [UserResourcesController::class, 'show']);
});
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
