<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialAuthController extends Controller
{
    /**
     * Redirigir a Google OAuth
     */
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Error al conectar con Google: ' . $e->getMessage());
        }
    }

    /**
     * Manejar callback de Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Buscar usuario existente por Google ID
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                // Usuario existe con Google ID
                if (!$user->is_active) {
                    return redirect('/login')->with('error', 'Tu cuenta está desactivada. Contacta al administrador.');
                }

                // Actualizar información si es necesaria
                $user->update([
                    'name' => $googleUser->name,
                    'avatar' => $googleUser->avatar,
                ]);

                Auth::login($user, true);
                return redirect()->intended('/dashboard');
            }

            // Verificar si existe usuario con el mismo email
            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                // Usuario existe pero sin Google ID - vincular cuenta
                if (!$existingUser->is_active) {
                    return redirect('/login')->with('error', 'Tu cuenta está desactivada. Contacta al administrador.');
                }

                $existingUser->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar ?? $existingUser->avatar,
                    'provider' => 'google', // Cambiar provider principal
                ]);

                Auth::login($existingUser, true);
                return redirect()->intended('/dashboard');
            }

            // Crear nuevo usuario
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'provider' => 'google',
                'is_active' => true,
                'email_verified_at' => now(), // Auto-verificar email de Google
            ]);

            Auth::login($newUser, true);
            return redirect()->intended('/dashboard');

        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Error en la autenticación con Google: ' . $e->getMessage());
        }
    }

    /**
     * Redirigir a Facebook OAuth
     */
    public function redirectToFacebook()
    {
        try {
            return Socialite::driver('facebook')->redirect();
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Error al conectar con Facebook: ' . $e->getMessage());
        }
    }

    /**
     * Manejar callback de Facebook
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            // Buscar usuario existente por Facebook ID
            $user = User::where('facebook_id', $facebookUser->id)->first();

            if ($user) {
                // Usuario existe con Facebook ID
                if (!$user->is_active) {
                    return redirect('/login')->with('error', 'Tu cuenta está desactivada. Contacta al administrador.');
                }

                // Actualizar información si es necesaria
                $user->update([
                    'name' => $facebookUser->name,
                    'avatar' => $facebookUser->avatar,
                ]);

                Auth::login($user, true);
                return redirect()->intended('/dashboard');
            }

            // Verificar si existe usuario con el mismo email
            $existingUser = User::where('email', $facebookUser->email)->first();

            if ($existingUser) {
                // Usuario existe pero sin Facebook ID - vincular cuenta
                if (!$existingUser->is_active) {
                    return redirect('/login')->with('error', 'Tu cuenta está desactivada. Contacta al administrador.');
                }

                $existingUser->update([
                    'facebook_id' => $facebookUser->id,
                    'avatar' => $facebookUser->avatar ?? $existingUser->avatar,
                    'provider' => 'facebook', // Cambiar provider principal
                ]);

                Auth::login($existingUser, true);
                return redirect()->intended('/dashboard');
            }

            // Crear nuevo usuario
            $newUser = User::create([
                'name' => $facebookUser->name,
                'email' => $facebookUser->email,
                'facebook_id' => $facebookUser->id,
                'avatar' => $facebookUser->avatar,
                'provider' => 'facebook',
                'is_active' => true,
                'email_verified_at' => now(), // Auto-verificar email de Facebook
            ]);

            Auth::login($newUser, true);
            return redirect()->intended('/dashboard');

        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Error en la autenticación con Facebook: ' . $e->getMessage());
        }
    }

    /**
     * Desvincular cuenta de Google
     */
    public function unlinkGoogle(Request $request)
    {
        $user = $request->user();

        // Verificar que el usuario tenga otra forma de autenticación
        if (!$user->password && !$user->facebook_id) {
            return back()->with('error', 'No puedes desvincular Google sin tener otra forma de acceso (contraseña o Facebook).');
        }

        $user->update([
            'google_id' => null,
            'provider' => $user->facebook_id ? 'facebook' : 'local',
        ]);

        return back()->with('success', 'Cuenta de Google desvinculada exitosamente.');
    }

    /**
     * Desvincular cuenta de Facebook
     */
    public function unlinkFacebook(Request $request)
    {
        $user = $request->user();

        // Verificar que el usuario tenga otra forma de autenticación
        if (!$user->password && !$user->google_id) {
            return back()->with('error', 'No puedes desvincular Facebook sin tener otra forma de acceso (contraseña o Google).');
        }

        $user->update([
            'facebook_id' => null,
            'provider' => $user->google_id ? 'google' : 'local',
        ]);

        return back()->with('success', 'Cuenta de Facebook desvinculada exitosamente.');
    }
}
