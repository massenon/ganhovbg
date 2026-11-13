<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Alertes VBG creees depuis le forum GANXO (serveur a serveur, signature HMAC)
Route::prefix('forum')->middleware(['forum.sig', 'throttle:60,1'])->group(function () {
    Route::get('/alert-config', [\App\Http\Controllers\Api\ForumAlertController::class, 'config']);
    Route::post('/alerts', [\App\Http\Controllers\Api\ForumAlertController::class, 'store']);
    Route::get('/alerts/{ticket}/messages', [\App\Http\Controllers\Api\ForumAlertController::class, 'messages']);
    Route::post('/alerts/{ticket}/messages', [\App\Http\Controllers\Api\ForumAlertController::class, 'reply']);
});
