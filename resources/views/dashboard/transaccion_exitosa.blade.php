@extends('layouts.app')

@section('content')
<style>
    .success-container {
        max-width: 600px;
        margin: 3rem auto;
        text-align: center;
        padding: 2rem;
    }

    .success-icon {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        animation: successPulse 2s ease-in-out infinite;
    }

    .success-icon i {
        font-size: 3rem;
    }

    @keyframes successPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .success-card {
        background: white;
        border-radius: 1rem;
        padding: 2.5rem;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .success-title {
        color: #28a745;
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .success-message {
        color: #6c757d;
        font-size: 1.1rem;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .transaction-details {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin: 2rem 0;
        text-align: left;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.8rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #e9ecef;
    }

    .detail-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
        font-weight: bold;
        font-size: 1.1rem;
        color: #28a745;
    }

    .detail-label {
        color: #6c757d;
        font-weight: 500;
    }

    .detail-value {
        color: #333;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 2rem;
    }

    .btn-dashboard {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 999px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-dashboard:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-download {
        background: #28a745;
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 999px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-download:hover {
        background: #218838;
        transform: translateY(-2px);
        color: white;
    }

    .btn-secondary-action {
        background: #6c757d;
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 999px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-secondary-action:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
    }

    .stepper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 2rem;
    }

    .stepper span {
        margin: 0 10px;
        font-weight: 600;
        color: #28a745;
        position: relative;
    }

    .stepper span.completed {
        color: #28a745;
    }

    .stepper span.active {
        color: #28a745;
        border-bottom: 2px solid #28a745;
        padding-bottom: 3px;
    }

    .confetti {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1000;
    }

    .next-steps {
        background: #e7f3ff;
        border: 1px solid #b8daff;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-top: 2rem;
        text-align: left;
    }

    .next-steps h6 {
        color: #004085;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .next-steps ul {
        color: #004085;
        margin: 0;
        padding-left: 1.5rem;
    }

    .next-steps li {
        margin-bottom: 0.5rem;
    }
</style>

<div class="success-container">
    <!-- Stepper -->
    <div class="stepper">
        <span class="completed">✓ Recibo</span>
        <span class="completed">✓ Pago</span>
        <span class="active">🎉 Transacción</span>
    </div>

    <!-- Icono de éxito -->
    <div class="success-icon">
        <i class="fas fa-check"></i>
    </div>

    <!-- Tarjeta principal -->
    <div class="success-card">
        <h1 class="success-title">¡Pago Exitoso!</h1>
        <p class="success-message">
            Tu pago ha sido procesado correctamente. Recibirás un comprobante por email y tu cuenta ha sido actualizada.
        </p>

        <!-- Detalles de la transacción -->
        <div class="transaction-details">
            <div class="detail-row">
                <span class="detail-label">📅 Fecha y hora:</span>
                <span class="detail-value">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">🔐 ID de Transacción:</span>
                <span class="detail-value">#{{ str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">📦 Concepto:</span>
                <span class="detail-value">Servicio de agua - {{ now()->locale('es')->translatedFormat('F Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">💳 Método de pago:</span>
                <span class="detail-value">Tarjeta de Crédito</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">💰 Total pagado:</span>
                <span class="detail-value">$5.00</span>
            </div>
        </div>

        <!-- Información de próximos pasos -->
        <div class="next-steps">
            <h6><i class="fas fa-info-circle me-2"></i>¿Qué sigue?</h6>
            <ul>
                <li>Tu cuenta ha sido actualizada automáticamente</li>
                <li>Recibirás un comprobante por email en los próximos minutos</li>
                <li>Tu próxima factura vencerá el 10 del próximo mes</li>
                <li>Puedes descargar el comprobante desde tu dashboard</li>
            </ul>
        </div>

        <!-- Botones de acción -->
        <div class="action-buttons">
            <a href="{{ route('dashboard.actualizar') }}" class="btn-dashboard">
                <i class="fas fa-tachometer-alt"></i>
                Ir al Dashboard
            </a>

            <a href="{{ route('recibo.pdf') }}" class="btn-download" target="_blank">
                <i class="fas fa-download"></i>
                Descargar PDF
            </a>

            <a href="{{ route('recibo.pagar') }}" class="btn-secondary-action">
                <i class="fas fa-plus"></i>
                Otro Pago
            </a>
        </div>
    </div>

    <!-- Mensaje de agradecimiento -->
    <div class="mt-4">
        <p class="text-muted">
            <i class="fas fa-heart text-danger me-1"></i>
            Gracias por confiar en ADACECAM. ¡Seguimos trabajando por brindarte el mejor servicio!
        </p>
    </div>
</div>

<!-- Efecto confetti -->
<div class="confetti" id="confetti"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Crear efecto confetti
    createConfetti();

    // Auto-ocultar confetti después de 3 segundos
    setTimeout(() => {
        document.getElementById('confetti').style.display = 'none';
    }, 3000);
});

function createConfetti() {
    const confettiContainer = document.getElementById('confetti');
    const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#6c5ce7', '#a0e7e5'];

    for (let i = 0; i < 100; i++) {
        const confetto = document.createElement('div');
        confetto.style.cssText = `
            position: absolute;
            width: 8px;
            height: 8px;
            background: ${colors[Math.floor(Math.random() * colors.length)]};
            left: ${Math.random() * 100}vw;
            animation: confetti-fall ${2 + Math.random() * 3}s linear infinite;
            animation-delay: ${Math.random() * 2}s;
        `;
        confettiContainer.appendChild(confetto);
    }

    // Agregar CSS para animación
    if (!document.querySelector('#confetti-styles')) {
        const style = document.createElement('style');
        style.id = 'confetti-styles';
        style.textContent = `
            @keyframes confetti-fall {
                0% {
                    transform: translateY(-100vh) rotate(0deg);
                    opacity: 1;
                }
                100% {
                    transform: translateY(100vh) rotate(360deg);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
}
</script>
@endsection
