@extends('layouts.app')

@section('content')
<style>
    body {
        font-family: 'Nunito', sans-serif;
    }
    .dashboard-card {
        min-height: 135px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
    .dashboard-card h5 {
        font-weight: 600;
    }
    .dashboard-card .fs-3 {
        font-size: 1.75rem !important;
    }
    .dashboard-button {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="container py-4">
    <h2 class="mb-4 text-primary fw-bold">
        💧 Bienvenido, {{ Auth::user()->name }} a <span class="text-uppercase">ADACECAM</span>
    </h2>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card text-center border-primary shadow-sm dashboard-card">
                <div class="card-body">
                    <h5 class="card-title">💵 Saldo Actual</h5>
                    <p class="card-text fs-3 text-success mb-0">$0.00</p>
                    <small>Sin deudas</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-danger shadow-sm dashboard-card">
                <div class="card-body">
                    <h5 class="card-title">📑 Recibos Pendientes</h5>
                    <p class="card-text fs-3 text-danger mb-0">2</p>
                    <small>¡Paga antes del 10 de junio!</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success shadow-sm dashboard-card">
                <div class="card-body">
                    <h5 class="card-title">🧾 Último Pago</h5>
                    <p class="card-text fs-6 mb-0">05/06/2025</p>
                    <small>#Recibo 2025-045</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 dashboard-button">
            <a href="#" class="btn btn-lg btn-primary w-100 fw-semibold rounded-pill">
                💳 Pagar Recibo
            </a>
        </div>
    </div>

    <div class="mt-4">
        <h4 class="mb-3 fw-bold">📋 Historial de Pagos</h4>
        <div class="table-responsive">
            <table class="table table-bordered align-middle table-hover shadow-sm">
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
                        <td><a href="#" class="btn btn-sm btn-primary rounded-pill">Pagar ahora</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
