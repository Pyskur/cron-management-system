<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CronController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\LimitController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\KeyController;
use App\Http\Controllers\Api\KeysCodeController;
use App\Http\Controllers\Api\KeysRefController;
use App\Http\Controllers\Api\KeysSmsController;
use App\Http\Controllers\Api\AutoRenewController;
use App\Http\Controllers\Api\EpdController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\HttpSettingController;


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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth', function (Request $request) {
        return $request->user();
    
    });
    Route::delete('auth/logout', [AuthController::class,'logout']);
});
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

// Test mode (should be in auth:sanctum)
Route::apiResource('users', UserController::class);
Route::apiResource('notifications', NotificationController::class);
Route::apiResource('keys', KeyController::class);
Route::apiResource('keys_codes', KeysCodeController::class);
Route::apiResource('keys_refs', KeysRefController::class);
Route::apiResource('keys_smses', KeysSmsController::class);
Route::apiResource('crons', CronController::class);
Route::apiResource('payments', CronController::class);
Route::apiResource('auto_renew', AutoRenewController::class);
Route::apiResource('companies', CompanyController::class);
Route::apiResource('epds', EpdController::class);
Route::apiResource('groups', GroupController::class);
Route::apiResource('http_settings', HttpSettingController::class);
Route::apiResource('limits', LimitController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('videos', VideoController::class);