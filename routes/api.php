<?php

use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\User\UserResourcesController;
use App\Http\Controllers\Api\User\UserStatsController;
use App\Http\Controllers\Api\User\UserWalletController;
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
Route::resource('users', UserController::class);
Route::prefix('users/{user}')->group(function () {
    Route::get('stats', [UserStatsController::class, 'show']);
    Route::get('wallet', [UserWalletController::class, 'show']);
    Route::get('resources', [UserResourcesController::class, 'show']);
});
