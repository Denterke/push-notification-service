<?php

use App\Http\Controllers\APNSController;
use App\Http\Middleware\VerifyRequest;
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

Route::group(['prefix' => '/apple/notification', 'middleware' => [VerifyRequest::class]], function () {
    Route::post('voip', [APNSController::class, 'sendVoipNotification']);
    Route::post('alert', [APNSController::class, 'sendAlertNotification']);
});

