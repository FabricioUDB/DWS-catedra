<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'tarjeta' => 'required|digits_between:13,16',
            'expiracion' => 'required|string',
            'cvv' => 'required|digits:3',
            'email' => 'required|email',
            'telefono' => 'required|string|max:20',
        ]);

        return view('dashboard.transaccion_exitosa');
    }

    public function generarPDF()
    {
        $data = [
            'estado' => 'Pagado',
            'fecha_pago' => now()->format('d/m/Y'),
            'hora_pago' => now()->format('H:i'),
            'id_transaccion' => '#12345678910',
            'concepto' => 'Producto 1',
            'metodo_pago' => 'PSE',
            'costo' => '$300.00',
            'total' => '$300.00'
        ];

        $pdf = Pdf::loadView('dashboard.transaccion_pdf', $data);

        return $pdf->download('comprobante_pago.pdf');
    }
}
