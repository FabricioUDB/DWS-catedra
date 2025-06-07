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
Route::middleware(['auth', 'verified', 'user.active'])->group(function () {

    // === DASHBOARD Y GESTIÓN DE PERFIL ===
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // NUEVA RUTA: Dashboard actualizado después de pago
    Route::get('/dashboard/actualizar', [DashboardController::class, 'actualizarDespuesPago'])->name('dashboard.actualizar');

    // Perfil del usuario
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::post('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.update-profile');

    // Configuraciones de seguridad
    Route::get('/dashboard/security', [DashboardController::class, 'security'])->name('dashboard.security');
    Route::post('/dashboard/password', [DashboardController::class, 'updatePassword'])->name('dashboard.update-password');
    Route::post('/dashboard/revoke-tokens', [DashboardController::class, 'revokeAllTokens'])->name('dashboard.revoke-tokens');
    Route::post('/dashboard/deactivate', [DashboardController::class, 'deactivateAccount'])->name('dashboard.deactivate');

    // === NUEVA FUNCIONALIDAD: DESCARGA DE PDF POR FACTURA ===
    Route::get('/dashboard/descargar-pdf/{facturaId}', [DashboardController::class, 'descargarPDF'])
         ->name('dashboard.descargar-pdf')
         ->where('facturaId', '[0-9]+'); // Solo acepta números

    // === RUTAS DE PAGOS ===
    // Paso 1: Validar recibo
    Route::get('/pagar-recibo', function () {
        return view('dashboard.pagar');
    })->name('recibo.pagar');

    Route::post('/validar-recibo', [ReciboController::class, 'validar'])->name('recibo.validar');

    // Paso 2: Formulario de tarjeta
    Route::get('/pago-tarjeta', [ReciboController::class, 'formularioPago'])->name('recibo.pago');

    // Paso 3: Procesar pago
    Route::post('/procesar-pago', [ReciboController::class, 'procesarPago'])->name('recibo.procesar');

    // PDF del recibo general (para pagos recién realizados)
    Route::get('/recibo/pdf', [ReciboController::class, 'generarPDF'])->name('recibo.pdf');

    // === GESTIÓN DE CUENTAS SOCIALES ===
    Route::delete('/auth/google/unlink', [SocialAuthController::class, 'unlinkGoogle'])->name('auth.google.unlink');
    Route::delete('/auth/facebook/unlink', [SocialAuthController::class, 'unlinkFacebook'])->name('auth.facebook.unlink');

    // === RUTAS ADICIONALES (compatibilidad con código existente) ===
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/settings', [DashboardController::class, 'security'])->name('settings');
    Route::get('/security', [DashboardController::class, 'security'])->name('security');
});

// === API ROUTES ===
Route::middleware(['auth:sanctum', 'user.active'])->prefix('api/v1')->group(function () {
    Route::get('/dashboard/stats', [DashboardController::class, 'apiStats'])->name('api.dashboard.stats');
});

// === RUTAS DE VERIFICACIÓN PÚBLICA ===
// Para verificar autenticidad de PDFs sin autenticación
Route::get('/verificar/{idTransaccion}', function ($idTransaccion) {
    // Aquí puedes implementar la lógica de verificación
    return view('verificacion.documento', compact('idTransaccion'));
})->name('verificar.documento')->where('idTransaccion', '[A-Za-z0-9\-#]+');

// Autenticación (Laravel Breeze / UI)
require __DIR__.'/auth.php';

// === PÁGINA PRINCIPAL ===
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : view('auth.login');
})->name('home');

// RUTA DEL BLOG

Route::get('/blog', [App\Http\Controllers\Web\BlogController::class, 'noticias'])->name('blog.noticias');

// === RUTAS DE FALLBACK PARA ERRORES ===
Route::fallback(function () {
    return Auth::check()
        ? redirect()->route('dashboard')->with('error', 'Página no encontrada. Redirigido al dashboard.')
        : redirect()->route('home')->with('error', 'Página no encontrada.');
});
