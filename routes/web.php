<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\AppointmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Rotte per gestire i parrucchieri
Route::get('/barbers', [BarberController::class, 'index'])->name('barbers.index');
Route::post('/barbers', [BarberController::class, 'store'])->name('barbers.store');
Route::put('/barbers/{id}', [BarberController::class, 'update']);
Route::delete('/barbers/{id}', [BarberController::class, 'destroy']);

// Rotte per gestire gli appuntamenti
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

// Rotte per ottenere il token CSRF
Route::get('/csrf-token', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});