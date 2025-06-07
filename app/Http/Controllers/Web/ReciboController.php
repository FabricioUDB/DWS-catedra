<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReciboController extends Controller
{
    public function validar(Request $request)
    {
        $request->validate([
            'codigo' => 'required|digits:6',
            'fecha' => 'required|date',
        ]);

        return redirect()->route('recibo.pago');
    }

    public function formularioPago()
    {
        return view('dashboard.pago_tarjeta');
    }

    public function procesarPago(Request $request)
    {
        // Validaciones más flexibles
        $request->validate([
            'nombre' => 'required|string|min:2|max:50|regex:/^[A-Za-záéíóúñÑ\s]+$/',
            'apellidos' => 'required|string|min:2|max:50|regex:/^[A-Za-záéíóúñÑ\s]+$/',
            'tarjeta' => 'required|string|min:13|max:19', // Acepta espacios
            'expiracion' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/'],
            'cvv' => 'required|string|min:3|max:4|regex:/^[0-9]+$/',
            'email' => 'nullable|string|max:100', // Opcional y sin validación estricta
            'telefono' => 'nullable|string|min:8|max:20', // Opcional
        ], [
            // Mensajes personalizados más amigables
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.regex' => 'El nombre solo puede contener letras',
            'apellidos.required' => 'Los apellidos son obligatorios',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras',
            'tarjeta.required' => 'El número de tarjeta es obligatorio',
            'tarjeta.min' => 'El número de tarjeta debe tener al menos 13 dígitos',
            'expiracion.required' => 'La fecha de expiración es obligatoria',
            'expiracion.regex' => 'La fecha debe tener el formato MM/YY',
            'cvv.required' => 'El código de seguridad es obligatorio',
            'cvv.regex' => 'El CVV solo puede contener números',
        ]);

        // Validación adicional de fecha de expiración (que no esté vencida)
        $expiracion = $request->expiracion;
        if (preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiracion, $matches)) {
            $mes = (int)$matches[1];
            $año = (int)('20' . $matches[2]);

            $fechaExpiracion = mktime(0, 0, 0, $mes + 1, 1, $año); // Primer día del mes siguiente
            $fechaActual = time();

            if ($fechaExpiracion <= $fechaActual) {
                return back()->withInput()->withErrors([
                    'expiracion' => 'La tarjeta está vencida'
                ]);
            }
        }

        // Limpiar número de tarjeta (quitar espacios)
        $numeroTarjeta = preg_replace('/\s+/', '', $request->tarjeta);

        // Marcar que se realizó un pago para actualizar el dashboard
        session(['pago_realizado' => true]);

        // Guardar datos del pago en sesión para el PDF
        session([
            'ultimo_pago' => [
                'nombre_completo' => $request->nombre . ' ' . $request->apellidos,
                'tarjeta_ultimos_4' => substr($numeroTarjeta, -4),
                'fecha_pago' => now(),
                'monto' => 5.00,
                'metodo' => 'Tarjeta de Crédito'
            ]
        ]);

        return view('dashboard.transaccion_exitosa');
    }

    /**
     * Generar PDF del recibo recién procesado (para la página de éxito)
     */
    public function generarPDF()
    {
        $user = Auth::user();

        // Obtener mes actual en español
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $mesActual = $meses[now()->month] . ' ' . now()->year;

        // Datos simulados del pago recién realizado
        $data = [
            'estado' => 'Pagado',
            'fecha_pago' => now()->format('d/m/Y'),
            'hora_pago' => now()->format('H:i'),
            'id_transaccion' => '#' . str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT),
            'concepto' => 'Servicio de agua - ' . $mesActual,
            'metodo_pago' => 'Tarjeta de Crédito',
            'costo' => '$5.00',
            'total' => '$5.00',
            'usuario' => $user->name,
            'email' => $user->email
        ];

        $pdf = Pdf::loadView('dashboard.transaccion_pdf', $data);

        return $pdf->download('comprobante_pago_' . now()->format('Y-m-d_H-i') . '.pdf');
    }
}
