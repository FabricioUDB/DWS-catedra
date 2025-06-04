<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Web\SocialAuthController;
use App\Http\Controllers\Web\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Ruta principal - redirigir según estado de autenticación
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('auth.login');
})->name('home');

// Rutas para invitados (no autenticados)
Route::middleware('guest')->group(function () {
    // Página de login (misma que la ruta principal)
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    // Procesar login
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    })->name('login.post');

    // Página de registro
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    // Procesar registro
    Route::post('/register', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'provider' => 'local',
            'is_active' => true,
        ]);

        Auth::login($user);
        return redirect('/dashboard');
    })->name('register.post');
});

// Rutas OAuth (disponibles para invitados)
Route::prefix('auth')->group(function () {
    // Google OAuth
    Route::get('/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    // Facebook OAuth
    Route::get('/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
    Route::get('/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');
});

// Rutas para usuarios autenticados
Route::middleware(['auth', 'user.active'])->group(function () {
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil de usuario
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/avatar', [DashboardController::class, 'updateAvatar'])->name('profile.avatar');

    // Configuración de seguridad
    Route::get('/security', [DashboardController::class, 'security'])->name('security');
    Route::post('/security/password', [DashboardController::class, 'updatePassword'])->name('security.password');
    Route::post('/security/two-factor', [DashboardController::class, 'enableTwoFactor'])->name('security.2fa');
    Route::delete('/security/two-factor', [DashboardController::class, 'disableTwoFactor'])->name('security.2fa.disable');
    Route::post('/security/deactivate', [DashboardController::class, 'deactivateAccount'])->name('security.deactivate');

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

// Rutas de verificación de email
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/dashboard')->with('success', 'Email ya verificado');
        }

        $user->markEmailAsVerified();
        return redirect('/dashboard')->with('success', 'Email verificado exitosamente');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Enlace de verificación enviado');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Rutas de recuperación de contraseña
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('/forgot-password', function (Request $request) {
        // Implementar lógica de recuperación
        return back()->with('success', 'Enlace de recuperación enviado (funcionalidad pendiente)');
    })->name('password.email');
});
