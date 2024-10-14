<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('appointments', [AppointmentController::class, 'storeAndRegisterUser']);
Route::post('request-access-link', [AuthController::class, 'requestAccessLink']);

Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::resource('appointments', AppointmentController::class);
});