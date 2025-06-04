<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (!$user->is_active) {
                // Para API requests
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tu cuenta está desactivada. Contacta al administrador.',
                        'error_code' => 'ACCOUNT_DEACTIVATED'
                    ], 403);
                }

                // Para web requests
                Auth::logout();
                return redirect('/login')->with('error', 'Tu cuenta está desactivada. Contacta al administrador.');
            }
        }

        return $next($request);
    }
}
