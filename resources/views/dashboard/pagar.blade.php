@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f8fafc;
    }

    .stepper {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 2rem;
        margin-bottom: 1.5rem;
    }

    .stepper span {
        font-weight: 600;
        color: #6c757d;
        position: relative;
    }

    .stepper span::before {
        content: "✔️";
        display: block;
        font-size: 1.5rem;
        margin-bottom: 0.2rem;
        text-align: center;
    }

    .stepper span.active {
        color:rgb(51, 117, 238);
        border-bottom: 2px solidrgb(45, 109, 247);
        padding-bottom: 4px;
    }

    .form-card {
        max-width: 450px;
        margin: 0 auto;
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
    }

    .form-title {
        font-weight: 700;
        font-size: 1.5rem;
    }

    .form-subtitle {
        font-size: 0.95rem;
        color: #6c757d;
        margin-bottom: 1.5rem;
    }

    .btn-confirmar {
        width: 100%;
        padding: 0.75rem;
        background-color:rgb(53, 98, 243);
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 0.5rem;
        transition: background-color 0.2s ease-in-out;
    }

    .btn-confirmar:hover {
        background-color: #3727d6;
    }

    .form-control {
        border-radius: 0.5rem;
    }
</style>

<div class="container py-5">
    <!-- Stepper -->
    <div class="stepper">
        <span class="active">Recibo</span>
        <span class="active">Pago</span>
        <span>Transacción</span>
    </div>

    <!-- Formulario -->
    <div class="form-card">
        <h3 class="form-title">Paga tu recibo, seguro y rápido</h3>
        <p class="form-subtitle">Ingresa los datos requeridos para continuar con el pago.</p>

        <form method="POST" action="{{ route('recibo.validar') }}">
            @csrf

            <div class="mb-3">
                <label for="codigo" class="form-label">6 primeros dígitos de tu recibo</label>
                <input type="text" id="codigo" name="codigo" maxlength="6" class="form-control" placeholder="6 primeros dígitos" required>
            </div>

            <div class="mb-4">
                <label for="fecha" class="form-label">Fecha de nacimiento</label>
                <input type="date" id="fecha" name="fecha" class="form-control" required>
            </div>

            <button type="submit" class="btn-confirmar">Confirmar</button>
        </form>
    </div>
</div>
@endsection
