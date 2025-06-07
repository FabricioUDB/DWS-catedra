<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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
     * Dashboard actualizado después de realizar un pago
     */
    public function actualizarDespuesPago(Request $request)
    {
        // Marcar que se realizó un pago reciente para simular la actualización
        session(['pago_realizado' => true]);

        // Redirigir al dashboard normal con mensaje de éxito
        return redirect()->route('dashboard')->with('success', '¡Pago procesado exitosamente! Tu cuenta ha sido actualizada.');
    }

    /**
     * Mostrar el dashboard principal
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Obtener facturas del usuario
        $facturas = $this->obtenerFacturasUsuario($user->id);

        // Calcular estadísticas dinámicas
        $estadisticas = $this->calcularEstadisticas($facturas);

        // Estadísticas adicionales del usuario
        $stats = [
            'login_count' => $user->tokens()->count(),
            'account_type' => $user->isOAuthUser() ? 'Social' : 'Local',
            'member_since' => $user->created_at->diffForHumans(),
            'last_login' => $user->updated_at->diffForHumans(),
        ];

        // Limpiar datos de pago de la sesión después de mostrar el dashboard actualizado
        if (session('ultimo_pago') || session('pago_realizado')) {
            session()->forget(['ultimo_pago', 'pago_realizado']);
        }

        return view('dashboard.index', compact('user', 'stats', 'facturas', 'estadisticas'));
    }

    /**
     * Obtener facturas del usuario (simulado o desde BD)
     */
    private function obtenerFacturasUsuario($userId)
    {
        // Verificar si hay un pago reciente
        $pagoRealizado = session('pago_realizado') || session('ultimo_pago');

        // Datos simulados para el ejemplo
        $facturas = collect([
            (object)[
                'id' => 1,
                'mes' => 'Mayo 2025',
                'monto' => 5.00,
                'estado' => 'Pagado',
                'fecha_pago' => '2025-06-05 14:30:00',
                'numero_recibo' => '2025-045',
                'fecha_vencimiento' => '2025-05-10'
            ],
            (object)[
                'id' => 2,
                'mes' => 'Junio 2025',
                'monto' => 5.00,
                'estado' => $pagoRealizado ? 'Pagado' : 'Pendiente',
                'fecha_pago' => $pagoRealizado ? now()->format('Y-m-d H:i:s') : null,
                'numero_recibo' => $pagoRealizado ? '2025-046' : null,
                'fecha_vencimiento' => '2025-06-10'
            ]
        ]);

        // Si hay un pago reciente, agregar factura de Julio como pendiente
        if ($pagoRealizado) {
            $facturas->push((object)[
                'id' => 3,
                'mes' => 'Julio 2025',
                'monto' => 5.00,
                'estado' => 'Pendiente',
                'fecha_pago' => null,
                'numero_recibo' => null,
                'fecha_vencimiento' => '2025-07-10'
            ]);
        }

        return $facturas;
    }

    /**
     * Calcular estadísticas dinámicas basadas en las facturas
     */
    private function calcularEstadisticas($facturas)
    {
        // Contar facturas pendientes
        $facturasPendientes = $facturas->where('estado', 'Pendiente');
        $recibosPendientes = $facturasPendientes->count();

        // Calcular saldo actual - SOLO facturas vencidas y no pagadas
        $facturasVencidas = $facturasPendientes->filter(function($factura) {
            return $factura->fecha_vencimiento < now();
        });

        $saldoActual = $facturasVencidas->sum('monto');

        // Obtener último pago
        $ultimoPago = $facturas->where('estado', 'Pagado')
                              ->sortByDesc('fecha_pago')
                              ->first();

        // Verificar si hay pagos vencidos
        $pagosVencidos = $facturasPendientes->where('fecha_vencimiento', '<', now())->count();

        return [
            'saldo_actual' => $saldoActual,
            'recibos_pendientes' => $recibosPendientes,
            'ultimo_pago' => $ultimoPago,
            'pagos_vencidos' => $pagosVencidos,
            'mensaje_urgencia' => $this->generarMensajeUrgencia($facturasPendientes)
        ];
    }

    /**
     * Generar mensaje de urgencia basado en fechas de vencimiento
     */
    private function generarMensajeUrgencia($facturasPendientes)
    {
        if ($facturasPendientes->isEmpty()) {
            return 'Tu pago está al día';
        }

        $proximaFecha = $facturasPendientes->min('fecha_vencimiento');
        $diasRestantes = now()->diffInDays($proximaFecha, false);

        if ($diasRestantes < 0) {
            return '¡Tienes pagos vencidos!';
        } elseif ($diasRestantes <= 3) {
            return "¡Paga antes del " . $this->formatearFecha($proximaFecha) . "!";
        } else {
            return "Próximo vencimiento: " . $this->formatearFecha($proximaFecha);
        }
    }

    /**
     * Formatear fecha en español
     */
    private function formatearFecha($fecha)
    {
        $meses = [
            'January' => 'enero', 'February' => 'febrero', 'March' => 'marzo',
            'April' => 'abril', 'May' => 'mayo', 'June' => 'junio',
            'July' => 'julio', 'August' => 'agosto', 'September' => 'septiembre',
            'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'
        ];

        $fechaFormateada = date('j \d\e F', strtotime($fecha));

        foreach ($meses as $ingles => $español) {
            $fechaFormateada = str_replace($ingles, $español, $fechaFormateada);
        }

        return $fechaFormateada;
    }

    /**
     * Generar PDF de recibo específico
     */
    public function descargarPDF($facturaId)
    {
        $user = Auth::user();

        // Obtener la factura específica
        $factura = $this->obtenerFacturaPorId($facturaId, $user->id);

        if (!$factura) {
            return back()->with('error', 'No se puede generar PDF. La factura no existe.');
        }

        if ($factura->estado !== 'Pagado') {
            return back()->with('error', 'No se puede generar PDF. La factura no está pagada.');
        }

        if (!$factura->numero_recibo) {
            return back()->with('error', 'No se puede generar PDF. La factura no tiene número de recibo.');
        }

        // Datos para el PDF
        $data = [
            'estado' => $factura->estado,
            'fecha_pago' => date('d/m/Y', strtotime($factura->fecha_pago)),
            'hora_pago' => date('H:i', strtotime($factura->fecha_pago)),
            'id_transaccion' => $factura->numero_recibo,
            'concepto' => 'Servicio de agua - ' . $factura->mes,
            'metodo_pago' => 'Tarjeta de Crédito',
            'costo' => '$' . number_format($factura->monto, 2),
            'total' => '$' . number_format($factura->monto, 2),
            'usuario' => $user->name,
            'email' => $user->email
        ];

        try {
            $pdf = Pdf::loadView('dashboard.transaccion_pdf', $data);
            return $pdf->download('recibo-' . $factura->numero_recibo . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Obtener factura específica por ID
     */
    private function obtenerFacturaPorId($facturaId, $userId)
    {
        // Obtener todas las facturas del usuario
        $facturas = $this->obtenerFacturasUsuario($userId);

        // Buscar la factura específica
        $factura = $facturas->where('id', $facturaId)->first();

        return $factura;
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

        $securityInfo = [
            'has_password' => !empty($user->password),
            'has_google' => !empty($user->google_id),
            'has_facebook' => !empty($user->facebook_id),
            'two_factor_enabled' => false,
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

        if ($user->password) {
            $rules['current_password'] = 'required|current_password';
        }

        $request->validate($rules);

        $user->update([
            'password' => bcrypt($request->password),
        ]);

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
        $currentToken = $user->currentAccessToken();
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
        $user->tokens()->delete();
        $user->update(['is_active' => false]);
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
