@extends('layouts.app')

@section('content')
<style>
    body {
        font-family: 'Nunito', sans-serif;
        background-color: #f8fafc;
    }

    .card-title i {
        margin-right: 8px;
    }

    .dashboard-section {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1);
    }

    .dashboard-card {
        border: none;
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        transition: 0.3s ease-in-out;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }

    .dashboard-button {
        text-align: center;
        margin-top: 1rem;
    }

    .dashboard-button .btn {
        font-size: 1.1rem;
        padding: 0.75rem 2rem;
        border-radius: 999px;
    }

    .logout-button {
        position: absolute;
        top: 20px;
        right: 30px;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.4rem 0.7rem;
        border-radius: 0.5rem;
    }
</style>

<div class="container py-4 position-relative">
    <!-- Botón Cerrar Sesión -->
    <form method="POST" action="{{ route('logout') }}" class="logout-button">
        @csrf
        <button type="submit" class="btn btn-outline-danger rounded-pill fw-semibold">
            🔒 Cerrar sesión
        </button>
    </form>

    <div class="dashboard-section">
        <!-- Encabezado -->
        <h2 class="mb-4 text-primary fw-bold">
            💧 Bienvenido, {{ Auth::user()->name }} a <span class="text-uppercase">ADACECAM</span>
        </h2>

        <!-- Tarjetas de resumen -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card dashboard-card bg-light text-success text-center">
                    <h5 class="card-title"><i class="bi bi-cash-coin"></i>Saldo Actual</h5>
                    <h3 class="fw-bold">$0.00</h3>
                    <small>Sin deudas</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card dashboard-card bg-light text-danger text-center">
                    <h5 class="card-title"><i class="bi bi-receipt"></i>Recibos Pendientes</h5>
                    <h3 class="fw-bold">2</h3>
                    <small>¡Paga antes del 10 de junio!</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card dashboard-card bg-light text-info text-center">
                    <h5 class="card-title"><i class="bi bi-calendar-check"></i>Último Pago</h5>
                    <p class="mb-0">05/06/2025</p>
                    <small>#Recibo 2025-045</small>
                </div>
            </div>
            <div class="col-md-3 dashboard-button">
                <a href="{{ route('recibo.pagar') }}" class="btn btn-primary w-100 fw-semibold rounded-pill">
                    💳 Pagar Recibo
                </a>
            </div>
        </div>

        <!-- Historial de Pagos -->
        <h4 class="mb-3 fw-bold">📋 Historial de Pagos</h4>
        <div class="table-responsive">
            <table class="table table-hover align-middle shadow-sm bg-white rounded">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Mes</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Fecha de Pago</th>
                        <th>Recibo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Mayo 2025</td>
                        <td>$5.00</td>
                        <td><span class="badge bg-success">Pagado</span></td>
                        <td>05/06/2025</td>
                        <td><a href="#" class="btn btn-sm btn-outline-secondary rounded-pill">Ver PDF</a></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Junio 2025</td>
                        <td>$5.00</td>
                        <td><span class="badge bg-danger">Pendiente</span></td>
                        <td>-</td>
                        <td><a href="{{ route('recibo.pagar') }}" class="btn btn-sm btn-primary rounded-pill">Pagar ahora</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
