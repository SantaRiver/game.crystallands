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

Route::prefix('users')->group(function () {
    Route::resource(null, UserController::class);
    Route::get('{user}/stats', [UserStatsController::class, 'show']);
    Route::get('{user}/wallet', [UserWalletController::class, 'show']);
    Route::get('{user}/resources', [UserResourcesController::class, 'show']);
});
