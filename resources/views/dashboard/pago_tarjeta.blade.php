@extends('layouts.app')

@section('content')
<style>
    .form-container {
        max-width: 700px;
        margin: 3rem auto;
        background: white;
        padding: 2rem 2.5rem;
        border-radius: 1rem;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    }

    .form-title {
        font-weight: bold;
        font-size: 1.6rem;
    }

    .stepper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .stepper span {
        margin: 0 10px;
        font-weight: 600;
    }

    .stepper span.active {
        border-bottom: 2px solid #3e63f0;
        padding-bottom: 3px;
    }

    .payment-icons {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        align-items: center;
        margin: 1rem 0 1.5rem;
    }

    .payment-icons img {
        height: 40px;
    }

    .btn-pagar {
        background-color: #3e63f0;
        color: white;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 999px;
        border: none;
        width: 100%;
    }
</style>

<div class="form-container">
    <div class="stepper">
        <span>Recibo</span>
        <span class="active">Pago</span>
        <span>Transacción</span>
    </div>

    <h3 class="form-title mb-3">Pago en línea</h3>
    <p>Paga tu factura de manera segura y rápida</p>

    <form method="POST" action="{{ route('recibo.procesar') }}">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Nombre del titular</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>Apellidos del titular</label>
                <input type="text" name="apellidos" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label>Número de tarjeta</label>
            <input type="text" name="tarjeta" maxlength="16" class="form-control" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Expiración (MM/YY)</label>
                <input type="text" name="expiracion" placeholder="MM/YY" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>Código de seguridad</label>
                <input type="text" name="cvv" maxlength="4" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="col-md-6">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control">
            </div>
        </div>

        <div class="payment-icons">
            <img src="https://img.icons8.com/color/48/visa.png" alt="Visa">
            <img src="https://img.icons8.com/color/48/mastercard-logo.png" alt="Mastercard">
            <img src="https://img.icons8.com/color/48/amex.png" alt="Amex">
            <img src="https://img.icons8.com/color/48/paypal.png" alt="Paypal">
        </div>

        <button type="submit" class="btn btn-pagar">💳 Pagar ahora</button>
    </form>
</div>
@endsection
