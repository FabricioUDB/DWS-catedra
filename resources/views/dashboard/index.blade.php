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
        position: relative;
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

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e9ecef;
    }

    .user-welcome {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-avatar-large {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .welcome-text h2 {
        margin: 0;
        color: #2c3e50;
        font-weight: bold;
    }

    .welcome-text p {
        margin: 0;
        color: #6c757d;
        font-size: 0.9rem;
    }

    /* Sección de estado solo con indicador "En línea" */
    .status-section {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
    }

    .user-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #6c757d;
    }

    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #28a745;
        animation: pulse-green 2s infinite;
    }

    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.4rem 0.7rem;
        border-radius: 0.5rem;
    }

    .alert-warning {
        border-left: 4px solid #f39c12;
    }

    .pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .status-section {
            align-items: center;
        }
    }
</style>

<div class="container py-4">
    <div class="dashboard-section">
        <!-- Header mejorado con usuario (sin botón logout) -->
        <div class="dashboard-header">
            <div class="user-welcome">
                <div class="user-avatar-large">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="welcome-text">
                    <h2>💧 Bienvenido, {{ Auth::user()->name }}</h2>
                    <p><span class="text-uppercase fw-bold">ADACECAM</span> - Panel de Control</p>
                </div>
            </div>

            <!-- Solo indicador de estado "En línea" -->
            <div class="status-section">
                <div class="user-status">
                    <span class="status-indicator"></span>
                    <span>En línea</span>
                </div>
            </div>
        </div>

        <!-- Alerta si hay pagos vencidos -->
        @if(isset($estadisticas['pagos_vencidos']) && $estadisticas['pagos_vencidos'] > 0)
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>
                <strong>¡Atención!</strong> Tienes {{ $estadisticas['pagos_vencidos'] }} pago(s) vencido(s).
                Por favor, regulariza tu situación lo antes posible.
            </div>
        </div>
        @endif

        <!-- Tarjetas de resumen dinámicas -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card dashboard-card bg-light text-center {{ $estadisticas['saldo_actual'] > 0 ? 'text-danger' : 'text-success' }}">
                    <h5 class="card-title"><i class="bi bi-cash-coin"></i>Saldo Actual</h5>
                    <h3 class="fw-bold">${{ number_format($estadisticas['saldo_actual'], 2) }}</h3>
                    <small>{{ $estadisticas['saldo_actual'] > 0 ? 'Con deudas' : 'Sin deudas' }}</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card dashboard-card bg-light text-center {{ $estadisticas['recibos_pendientes'] > 0 ? 'text-warning' : 'text-success' }} {{ $estadisticas['pagos_vencidos'] > 0 ? 'pulse' : '' }}">
                    <h5 class="card-title"><i class="bi bi-receipt"></i>Recibos Pendientes</h5>
                    <h3 class="fw-bold">{{ $estadisticas['recibos_pendientes'] }}</h3>
                    <small>{{ $estadisticas['mensaje_urgencia'] }}</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card dashboard-card bg-light text-info text-center">
                    <h5 class="card-title"><i class="bi bi-calendar-check"></i>Último Pago</h5>
                    @if($estadisticas['ultimo_pago'])
                        <p class="mb-0">{{ date('d/m/Y', strtotime($estadisticas['ultimo_pago']->fecha_pago)) }}</p>
                        <small>#{{ $estadisticas['ultimo_pago']->numero_recibo }}</small>
                    @else
                        <p class="mb-0">Sin pagos</p>
                        <small>No hay historial</small>
                    @endif
                </div>
            </div>
            <div class="col-md-3 dashboard-button">
                @if($estadisticas['recibos_pendientes'] > 0)
                    <a href="{{ route('recibo.pagar') }}" class="btn btn-primary w-100 fw-semibold rounded-pill">
                        💳 Pagar Recibo
                    </a>
                @else
                    <button class="btn btn-success w-100 fw-semibold rounded-pill" disabled>
                        ✅ Al día con pagos
                    </button>
                @endif
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
                    @forelse($facturas as $index => $factura)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $factura->mes }}</td>
                        <td>${{ number_format($factura->monto, 2) }}</td>
                        <td>
                            @if($factura->estado === 'Pagado')
                                <span class="badge bg-success">Pagado</span>
                            @elseif($factura->estado === 'Pendiente')
                                @if($factura->fecha_vencimiento < now())
                                    <span class="badge bg-danger">Vencido</span>
                                @else
                                    <span class="badge bg-warning">Pendiente</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            {{ $factura->fecha_pago ? date('d/m/Y', strtotime($factura->fecha_pago)) : '-' }}
                        </td>
                        <td>
                            @if($factura->estado === 'Pagado' && $factura->numero_recibo)
                                <a href="{{ route('dashboard.descargar-pdf', $factura->id) }}"
                                   class="btn btn-sm btn-outline-secondary rounded-pill">
                                    <i class="bi bi-download"></i> Descargar PDF
                                </a>
                            @elseif($factura->estado === 'Pendiente')
                                <a href="{{ route('recibo.pagar') }}"
                                   class="btn btn-sm btn-primary rounded-pill">
                                    💳 Pagar ahora
                                </a>
                            @else
                                <span class="text-muted">No disponible</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No hay historial de pagos disponible
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($facturas->count() > 0)
        <div class="mt-3 text-center">
            <small class="text-muted">
                <i class="bi bi-info-circle"></i>
                Total de registros: {{ $facturas->count() }} |
                Pagados: {{ $facturas->where('estado', 'Pagado')->count() }} |
                Pendientes: {{ $facturas->where('estado', 'Pendiente')->count() }}
            </small>
        </div>
        @endif
    </div>
</div>

<!-- Toast para notificaciones -->
@if(session('success'))
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast show" role="alert">
        <div class="toast-header">
            <strong class="me-auto text-success">Éxito</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast show" role="alert">
        <div class="toast-header">
            <strong class="me-auto text-danger">Error</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
// Auto-hide toasts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(function(toast) {
        setTimeout(function() {
            toast.classList.remove('show');
        }, 5000);
    });
});
</script>
@endpush
