<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\HomePageController;

// Rotte per la homepage e la dashboard rimangono le stesse
Route::get('/', [HomePageController::class, 'index'])->name('homepage.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rotte per gestire il profilo utente
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include le rotte di autenticazione generate da Laravel
require __DIR__ . '/auth.php';

// Rotte per gestire i parrucchieri
Route::get('/barbers', [BarberController::class, 'index'])->name('barbers.index');
Route::post('/barbers', [BarberController::class, 'store'])->name('barbers.store');
Route::put('/barbers/{id}', [BarberController::class, 'update']);
Route::delete('/barbers/{id}', [BarberController::class, 'destroy']);

// Rotte per gestire gli appuntamenti
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy']);
Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');

// Rotte per ottenere il token CSRF
Route::get('/csrf-token', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});
