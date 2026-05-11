<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/me', [AuthController::class, 'me'])
        ->middleware('auth:api');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:api');
});
