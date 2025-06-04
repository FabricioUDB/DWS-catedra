<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Constructor - aplicar middleware de autenticación
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'user.active']);
    }

    /**
     * Mostrar el dashboard principal
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Estadísticas básicas del usuario
        $stats = [
            'login_count' => $user->tokens()->count(),
            'account_type' => $user->isSocialUser() ? 'Social' : 'Local',
            'member_since' => $user->created_at->diffForHumans(),
            'last_login' => $user->updated_at->diffForHumans(),
        ];

        return view('dashboard.index', compact('user', 'stats'));
    }

    /**
     * Mostrar perfil del usuario
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return view('dashboard.profile', compact('user'));
    }

    /**
     * Mostrar configuraciones de seguridad
     */
    public function security(Request $request)
    {
        $user = $request->user();

        // Información de seguridad
        $securityInfo = [
            'has_password' => !empty($user->password),
            'has_google' => !empty($user->google_id),
            'has_facebook' => !empty($user->facebook_id),
            'two_factor_enabled' => false, // Para implementar después
            'active_sessions' => $user->tokens()->count(),
        ];

        return view('dashboard.security', compact('user', 'securityInfo'));
    }

    /**
     * Actualizar perfil
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Perfil actualizado exitosamente.');
    }

    /**
     * Actualizar contraseña
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $rules = [
            'password' => 'required|string|min:8|confirmed',
        ];

        // Si el usuario ya tiene contraseña, requerir la actual
        if ($user->password) {
            $rules['current_password'] = 'required|current_password';
        }

        $request->validate($rules);

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        // Si es la primera vez que establece contraseña, cambiar provider a local
        if (!$user->password && $user->provider !== 'local') {
            $user->update(['provider' => 'local']);
        }

        return back()->with('success', 'Contraseña actualizada exitosamente.');
    }

    /**
     * Revocar todos los tokens de acceso
     */
    public function revokeAllTokens(Request $request)
    {
        $user = $request->user();

        // Mantener el token actual
        $currentToken = $user->currentAccessToken();

        // Revocar todos los otros tokens
        $user->tokens()->where('id', '!=', $currentToken->id)->delete();

        return back()->with('success', 'Todas las otras sesiones han sido cerradas.');
    }

    /**
     * Desactivar cuenta
     */
    public function deactivateAccount(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|in:DEACTIVATE',
        ]);

        $user = $request->user();

        // Revocar todos los tokens
        $user->tokens()->delete();

        // Desactivar cuenta
        $user->update(['is_active' => false]);

        // Cerrar sesión
        Auth::logout();

        return redirect('/')->with('success', 'Tu cuenta ha sido desactivada exitosamente.');
    }

    /**
     * API: Obtener estadísticas del usuario
     */
    public function apiStats(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'user_info' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar_url' => $user->avatar_url,
                    'provider' => $user->provider,
                    'member_since' => $user->created_at->toDateString(),
                ],
                'security' => [
                    'has_password' => !empty($user->password),
                    'has_google' => !empty($user->google_id),
                    'has_facebook' => !empty($user->facebook_id),
                    'active_tokens' => $user->tokens()->count(),
                ],
                'activity' => [
                    'last_login' => $user->updated_at->toISOString(),
                    'account_status' => $user->is_active ? 'active' : 'inactive',
                ]
            ]
        ]);
    }
}
