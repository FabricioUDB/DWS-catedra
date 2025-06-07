<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Web\SocialAuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ReciboController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rutas OAuth (Google / Facebook)
Route::prefix('auth')->group(function () {
    Route::get('/google', [SocialAuthController::class, 'redirectToGoogle'])
        ->name('auth.google')
        ->withoutMiddleware(['auth', 'verified']);

    Route::get('/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])
        ->name('auth.google.callback')
        ->withoutMiddleware(['auth', 'verified', 'cors']);

    Route::get('/facebook', [SocialAuthController::class, 'redirectToFacebook'])
        ->name('auth.facebook')
        ->withoutMiddleware(['auth', 'verified']);

    Route::get('/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])
        ->name('auth.facebook.callback')
        ->withoutMiddleware(['auth', 'verified', 'cors']);
});

// Rutas protegidas por autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard y ajustes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::get('/security', [DashboardController::class, 'settings'])->name('security');

    // Paso 1: Validar recibo
    Route::get('/pagar-recibo', function () {
        return view('dashboard.pagar');
    })->name('recibo.pagar');

    Route::post('/validar-recibo', [ReciboController::class, 'validar'])->name('recibo.validar');

    // Paso 2: Formulario de tarjeta
    Route::get('/pago-tarjeta', [ReciboController::class, 'formularioPago'])->name('recibo.pago');

    // Paso 3: Procesar pago
    Route::post('/procesar-pago', [ReciboController::class, 'procesarPago'])->name('recibo.procesar');

    // ✅ Nueva Ruta: Generar PDF del recibo
    Route::get('/recibo/pdf', [ReciboController::class, 'generarPDF'])->name('recibo.pdf');

    // Desvincular cuentas sociales
    Route::delete('/auth/google/unlink', [SocialAuthController::class, 'unlinkGoogle'])->name('auth.google.unlink');
    Route::delete('/auth/facebook/unlink', [SocialAuthController::class, 'unlinkFacebook'])->name('auth.facebook.unlink');
});

// Autenticación (Laravel Breeze / UI)
require __DIR__.'/auth.php';

// Página principal
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : view('auth.login');
})->name('home');
