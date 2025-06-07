<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 40px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .container {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 30px;
        }
        .row {
            margin-bottom: 12px;
        }
        .label {
            font-weight: bold;
            color: #2c3e50;
        }
        .value {
            margin-left: 5px;
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }
        .highlight {
            font-weight: bold;
            font-size: 18px;
            color: #27ae60;
        }
    </style>
</head>
<body>
    <h1>💳 Comprobante de Pago</h1>

    <div class="container">
        <div class="row">
            <span class="label">🧾 Estado:</span>
            <span class="value">{{ $estado }}</span>
        </div>
        <div class="row">
            <span class="label">📅 Fecha:</span>
            <span class="value">{{ $fecha_pago }}</span>
        </div>
        <div class="row">
            <span class="label">⏰ Hora:</span>
            <span class="value">{{ $hora_pago }}</span>
        </div>
        <div class="row">
            <span class="label">🔐 ID de Transacción:</span>
            <span class="value">{{ $id_transaccion }}</span>
        </div>
        <div class="row">
            <span class="label">📦 Concepto:</span>
            <span class="value">{{ $concepto }}</span>
        </div>
        <div class="row">
            <span class="label">💳 Método de Pago:</span>
            <span class="value">{{ $metodo_pago }}</span>
        </div>
        <div class="row">
            <span class="label">💰 Costo:</span>
            <span class="value">{{ $costo }}</span>
        </div>
        <div class="row">
            <span class="label highlight">Total:</span>
            <span class="value highlight">{{ $total }}</span>
        </div>
    </div>

    <p class="footer">Este comprobante ha sido generado electrónicamente por el sistema ADACECAM. No requiere firma ni sello.</p>
</body>
</html>
