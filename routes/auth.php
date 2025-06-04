<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// =======================
// LOGIN ROUTES
// =======================

// Mostrar formulario de login
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');

// Procesar login
Route::post('/login', function () {
    $credentials = request()->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, request()->boolean('remember'))) {
        request()->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'Las credenciales no coinciden con nuestros registros.',
    ])->onlyInput('email');
})->name('login.post');

// =======================
// REGISTER ROUTES
// =======================

// Mostrar formulario de registro
Route::get('/register', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.register');
})->name('register');

// Procesar registro
Route::post('/register', function () {
    $validated = request()->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'provider' => 'local',
        'is_active' => true,
    ]);

    Auth::login($user);

    return redirect()->route('dashboard');
})->name('register.post');

// =======================
// LOGOUT ROUTE
// =======================

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// =======================
// PASSWORD RESET ROUTES
// =======================

// Mostrar formulario "¿Olvidaste tu contraseña?"
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Enviar enlace de recuperación
Route::post('/forgot-password', function () {
    $request = request();
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => 'Te hemos enviado un enlace de recuperación por email.'])
                : back()->withErrors(['email' => 'No encontramos un usuario con ese email.']);
})->name('password.email');

// Mostrar formulario de reset con token
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => request()->email
    ]);
})->name('password.reset');

// Procesar reset de contraseña
Route::post('/reset-password', function () {
    $request = request();
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ]);
            $user->save();
            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', 'Tu contraseña ha sido restablecida exitosamente.')
                : back()->withErrors(['email' => 'Hubo un problema al restablecer tu contraseña.']);
})->name('password.update');

// =======================
// EMAIL VERIFICATION ROUTES
// =======================

Route::middleware(['auth'])->group(function () {

    // Página de aviso de verificación
    Route::get('/email/verify', function () {
        if (request()->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }
        return view('auth.verify-email');
    })->name('verification.notice');

    // Reenviar email de verificación
    Route::post('/email/verification-notification', function () {
        if (request()->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        request()->user()->sendEmailVerificationNotification();
        return back()->with('message', '¡Enlace de verificación enviado!');
    })->name('verification.send');

    // Verificar email con token
    Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
        $user = \App\Models\User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Token de verificación inválido.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('message', 'Email ya verificado.');
        }

        if ($user->markEmailAsVerified()) {
            // Email verificado exitosamente
        }

        return redirect()->route('dashboard')->with('message', '¡Email verificado exitosamente!');
    })->name('verification.verify');
});

// =======================
// PROFILE MANAGEMENT ROUTES
// =======================

Route::middleware(['auth', 'verified'])->group(function () {

    // Actualizar perfil
    Route::put('/profile', function () {
        $user = request()->user();

        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $emailChanged = $user->email !== $validated['email'];

        $user->update($validated);

        if ($emailChanged) {
            $user->email_verified_at = null;
            $user->save();
            $user->sendEmailVerificationNotification();
        }

        return back()->with('status', 'Perfil actualizado exitosamente.');
    })->name('profile.update');

    // Cambiar contraseña
    Route::put('/password', function () {
        $validated = request()->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], request()->user()->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        request()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'Contraseña actualizada exitosamente.');
    })->name('password.change');

    // Eliminar cuenta
    Route::delete('/profile', function () {
        $validated = request()->validate([
            'password' => 'required',
        ]);

        $user = request()->user();

        if (!Hash::check($validated['password'], $user->password)) {
            return back()->withErrors(['password' => 'La contraseña es incorrecta.']);
        }

        Auth::logout();
        $user->delete();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('profile.destroy');
});

// =======================
// TWO FACTOR AUTHENTICATION (Si usas Laravel Fortify)
// =======================

Route::middleware(['auth'])->group(function () {

    // Mostrar códigos de recuperación
    Route::get('/user/two-factor-recovery-codes', function () {
        return view('auth.two-factor-recovery-codes', [
            'recoveryCodes' => json_decode(decrypt(
                request()->user()->two_factor_recovery_codes
            ), true)
        ]);
    })->name('two-factor.recovery-codes');

    // Habilitar 2FA
    Route::post('/user/two-factor-authentication', function () {
        request()->user()->enableTwoFactorAuthentication();
        return back()->with('status', 'Autenticación de dos factores habilitada.');
    })->name('two-factor.enable');

    // Deshabilitar 2FA
    Route::delete('/user/two-factor-authentication', function () {
        request()->user()->disableTwoFactorAuthentication();
        return back()->with('status', 'Autenticación de dos factores deshabilitada.');
    })->name('two-factor.disable');
});

// =======================
// API TOKEN MANAGEMENT (Si usas Sanctum)
// =======================

Route::middleware(['auth', 'verified'])->group(function () {

    // Crear token API
    Route::post('/user/api-tokens', function () {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
        ]);

        $token = request()->user()->createToken($validated['name']);

        return back()->with([
            'token' => explode('|', $token->plainTextToken, 2)[1],
            'status' => 'Token API creado exitosamente.'
        ]);
    })->name('api-tokens.store');

    // Eliminar token API
    Route::delete('/user/api-tokens/{tokenId}', function ($tokenId) {
        request()->user()->tokens()->where('id', $tokenId)->delete();
        return back()->with('status', 'Token API eliminado.');
    })->name('api-tokens.destroy');
});
