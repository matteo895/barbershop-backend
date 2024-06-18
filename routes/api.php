<?php

use App\Http\Controllers\BarberController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::apiResource('barbers', BarberController::class);
    Route::apiResource('appointments', AppointmentController::class);
});
