<?php

use App\Http\Controllers\Web\SocialAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes - OAuth Routes
|--------------------------------------------------------------------------
*/

// Rutas OAuth - SIN middleware problemático
Route::prefix('auth')->group(function () {

    // Google OAuth
    Route::get('/google', [SocialAuthController::class, 'redirectToGoogle'])
        ->name('auth.google')
        ->withoutMiddleware(['auth', 'verified']); // Excluir middlewares problemáticos

    Route::get('/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])
        ->name('auth.google.callback')
        ->withoutMiddleware(['auth', 'verified', 'cors']); // Excluir CORS también

    // Facebook OAuth
    Route::get('/facebook', [SocialAuthController::class, 'redirectToFacebook'])
        ->name('auth.facebook')
        ->withoutMiddleware(['auth', 'verified']);

    Route::get('/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])
        ->name('auth.facebook.callback')
        ->withoutMiddleware(['auth', 'verified', 'cors']);
});

// Rutas protegidas por autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Web\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Web\DashboardController::class, 'profile'])->name('profile');
    Route::get('/settings', [App\Http\Controllers\Web\DashboardController::class, 'settings'])->name('settings');
    Route::get('/security', [App\Http\Controllers\Web\DashboardController::class, 'settings'])->name('security'); // ✅ Agregada

    // Rutas para desvincular cuentas
    Route::delete('/auth/google/unlink', [SocialAuthController::class, 'unlinkGoogle'])->name('auth.google.unlink');
    Route::delete('/auth/facebook/unlink', [SocialAuthController::class, 'unlinkFacebook'])->name('auth.facebook.unlink');

    // ✅ Ruta para cerrar sesión
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});

// Rutas de autenticación básica (si tienes Laravel Breeze/UI instalado)
require __DIR__.'/auth.php';

// Ruta principal - mostrar login o redirigir al dashboard
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('home');
