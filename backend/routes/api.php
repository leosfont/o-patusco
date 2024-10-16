<?php

use App\Http\Controllers\AnimalTypeController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DoctorController;
use App\Http\Middleware\CheckPermissions;
use Illuminate\Support\Facades\Route;

Route::post('create-first-appointment', [AppointmentController::class, 'createFirst']);
Route::post('request-access-link', [AuthController::class, 'requestAccessLink']);
Route::get('animal-types', [AnimalTypeController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', [AuthController::class, 'getUserAuthenticated'])->name('auth.user');

    Route::middleware(CheckPermissions::class)->group(function () {
        
        Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::post('appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::put('appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
        
        Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('doctors', [DoctorController::class, 'index'])->name('doctors.index');
    });
});
