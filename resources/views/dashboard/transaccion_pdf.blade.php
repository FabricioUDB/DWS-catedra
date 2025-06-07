<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago - ADACECAM</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 20px;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .document-title {
            font-size: 20px;
            color: #3498db;
            margin-bottom: 5px;
        }

        .document-number {
            font-size: 14px;
            color: #7f8c8d;
        }

        .container {
            border: 1px solid #bdc3c7;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 5px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 40%;
            font-weight: bold;
            color: #2c3e50;
            padding: 8px 0;
            vertical-align: top;
        }

        .info-value {
            display: table-cell;
            width: 60%;
            padding: 8px 0 8px 15px;
            vertical-align: top;
            border-left: 2px solid #ecf0f1;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        .status-paid {
            background-color: #27ae60;
            color: white;
        }

        .highlight {
            font-weight: bold;
            font-size: 18px;
            color: #27ae60;
            background-color: #d5f4e6;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #bdc3c7;
            font-size: 11px;
            color: #7f8c8d;
            text-align: center;
            line-height: 1.4;
        }

        .qr-section {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background-color: #ecf0f1;
            border-radius: 5px;
        }

        .timestamp {
            text-align: right;
            font-size: 10px;
            color: #95a5a6;
            margin-top: 10px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(52, 152, 219, 0.1);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="watermark">ADACECAM</div>

    <div class="header">
        <div class="company-name">💧 ADACECAM</div>
        <div class="document-title">Comprobante de Pago</div>
        <div class="document-number">Documento N° {{ $id_transaccion }}</div>
    </div>

    <!-- Información del Cliente -->
    <div class="container">
        <div class="section-title">📋 Información del Cliente</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">👤 Cliente:</div>
                <div class="info-value">{{ $usuario ?? 'Usuario' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">📧 Email:</div>
                <div class="info-value">{{ $email ?? 'correo@ejemplo.com' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">📅 Fecha de emisión:</div>
                <div class="info-value">{{ now()->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Detalles de la Transacción -->
    <div class="container">
        <div class="section-title">💳 Detalles de la Transacción</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">🧾 Estado:</div>
                <div class="info-value">
                    <span class="status-badge status-paid">{{ $estado }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">📅 Fecha de Pago:</div>
                <div class="info-value">{{ $fecha_pago }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">⏰ Hora de Pago:</div>
                <div class="info-value">{{ $hora_pago }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">🔐 ID de Transacción:</div>
                <div class="info-value">{{ $id_transaccion }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">📦 Concepto:</div>
                <div class="info-value">{{ $concepto }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">💳 Método de Pago:</div>
                <div class="info-value">{{ $metodo_pago }}</div>
            </div>
        </div>
    </div>

    <!-- Resumen Financiero -->
    <div class="container">
        <div class="section-title">💰 Resumen Financiero</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Subtotal:</div>
                <div class="info-value">{{ $costo }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Impuestos:</div>
                <div class="info-value">$0.00</div>
            </div>
            <div class="info-row">
                <div class="info-label">Descuentos:</div>
                <div class="info-value">$0.00</div>
            </div>
        </div>

        <div class="highlight">
            💰 TOTAL PAGADO: {{ $total }}
        </div>
    </div>

    <!-- Código QR para verificación -->
    <div class="qr-section">
        <div style="font-size: 12px; margin-bottom: 10px;">
            <strong>Verificación del documento:</strong>
        </div>
        <div style="font-size: 10px; color: #7f8c8d;">
            Para verificar la autenticidad de este documento,
            visite: www.adacecam.com/verificar/{{ $id_transaccion }}
        </div>
    </div>

    <!-- Información Adicional -->
    <div class="container">
        <div class="section-title">ℹ️ Información Adicional</div>
        <div style="font-size: 12px; line-height: 1.8;">
            <p><strong>Política de reembolsos:</strong> Los pagos realizados no son reembolsables una vez procesados.</p>
            <p><strong>Validez:</strong> Este comprobante es válido por tiempo indefinido como prueba de pago.</p>
            <p><strong>Soporte:</strong> Para cualquier consulta, contacte a soporte@adacecam.com o llame al (503) 2XXX-XXXX.</p>
            <p><strong>Próximo vencimiento:</strong> Su próxima factura vencerá el 10 del próximo mes.</p>
        </div>
    </div>

    <div class="timestamp">
        Generado automáticamente el {{ now()->format('d/m/Y \a \l\a\s H:i:s') }} | Sistema ADACECAM v2.0
    </div>

    <div class="footer">
        <p><strong>ADACECAM - Asociación de Acueductos Comunales</strong></p>
        <p>Este comprobante ha sido generado electrónicamente por el sistema ADACECAM.</p>
        <p>No requiere firma ni sello para su validez legal.</p>
        <p>Documento protegido contra alteraciones - Cualquier modificación invalidará este comprobante</p>
        <hr style="margin: 10px 0; border: none; border-top: 1px solid #bdc3c7;">
        <p style="font-size: 10px;">
            © {{ now()->year }} ADACECAM. Todos los derechos reservados. |
            Generado en: Santa Tecla, La Libertad, El Salvador
        </p>
    </div>
</body>
</html>
