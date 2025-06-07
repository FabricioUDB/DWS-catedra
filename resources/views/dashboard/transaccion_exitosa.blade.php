@extends('layouts.app')

@section('content')
<style>
    .stepper {
        display: flex;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .stepper span {
        position: relative;
        margin: 0 1.5rem;
        padding-bottom: 0.25rem;
    }

    .stepper span::after {
        content: '';
        position: absolute;
        left: 50%;
        bottom: -6px;
        transform: translateX(-50%);
        width: 10px;
        height: 10px;
        background-color: #3b82f6;
        border-radius: 50%;
    }

    .transaction-box {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1.2rem rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .transaction-box h2 {
        font-weight: bold;
        margin-bottom: 1rem;
        color: #10b981;
    }

    .transaction-box .details {
        text-align: left;
        margin-top: 1.5rem;
    }

    .details p {
        margin: 0.4rem 0;
    }

    .btn-descargar {
        margin-top: 2rem;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        border-radius: 999px;
    }
</style>

<div class="container py-4">
    <!-- Indicador de pasos -->
    <div class="stepper">
        <span>Recibo</span>
        <span>Pago</span>
        <span><strong>Transacción</strong></span>
    </div>

    <!-- Caja de éxito -->
    <div class="transaction-box">
        <h2>✅ Transacción Exitosa</h2>
        <p class="text-muted">Gracias por tu pago. A continuación los detalles de la operación:</p>

        <div class="details mt-4">
            <p><strong>Estado:</strong> Completado</p>
            <p><strong>Fecha de pago:</strong> {{ now()->format('d/m/Y') }}</p>
            <p><strong>Hora de pago:</strong> {{ now()->format('H:i') }}</p>
            <p><strong>ID de Transacción:</strong> #123456789</p>
            <p><strong>Concepto:</strong> Pago de recibo de agua</p>
            <p><strong>Método de pago:</strong> Tarjeta</p>
            <p><strong>Costo subtotal:</strong> $5.00</p>
            <p><strong>Total:</strong> $5.00</p>
        </div>

        <a href="{{ route('recibo.pdf') }}" class="btn btn-outline-primary btn-descargar">
            📄 Descargar PDF
        </a>
    </div>
</div>
@endsection
