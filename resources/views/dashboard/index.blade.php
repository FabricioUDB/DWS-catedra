@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0">
            <div class="sidebar">
                <div class="p-3">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ $user->avatar_url }}" alt="Avatar" class="avatar me-3">
                        <div>
                            <h6 class="mb-0 text-white">{{ $user->name }}</h6>
                            <small class="text-muted">{{ ucfirst($user->provider) }}</small>
                        </div>
                    </div>

                    <nav class="nav flex-column">
                        <a class="nav-link active" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('profile') }}">
                            <i class="fas fa-user me-2"></i>Perfil
                        </a>
                        <a class="nav-link" href="{{ route('security') }}">
                            <i class="fas fa-shield-alt me-2"></i>Seguridad
                        </a>
                        <hr class="text-muted">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </button>
                        </form>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0">¡Bienvenido, {{ $user->name }}!</h1>
                        <p class="text-muted mb-0">Gestiona tu cuenta y configuraciones desde aquí</p>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">Último acceso</small><br>
                        <small><strong>{{ $stats['last_login'] }}</strong></small>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <div class="text-primary mb-2">
                                    <i class="fas fa-user-check" style="font-size: 2rem;"></i>
                                </div>
                                <h5 class="card-title">Estado de Cuenta</h5>
                                <span class="badge bg-success">Activa</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <div class="text-info mb-2">
                                    <i class="fas fa-sign-in-alt" style="font-size: 2rem;"></i>
                                </div>
                                <h5 class="card-title">Tipo de Cuenta</h5>
                                <span class="badge bg-info">{{ $stats['account_type'] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <div class="text-success mb-2">
                                    <i class="fas fa-calendar-alt" style="font-size: 2rem;"></i>
                                </div>
                                <h5 class="card-title">Miembro desde</h5>
                                <small class="text-muted">{{ $stats['member_since'] }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <div class="text-warning mb-2">
                                    <i class="fas fa-key" style="font-size: 2rem;"></i>
                                </div>
                                <h5 class="card-title">Tokens Activos</h5>
                                <span class="badge bg-warning text-dark">{{ $stats['login_count'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Información de la Cuenta
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nombre:</strong> {{ $user->name }}</p>
                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                        <p><strong>Proveedor:</strong>
                                            @if($user->provider === 'google')
                                                <i class="fab fa-google text-danger"></i> Google
                                            @elseif($user->provider === 'facebook')
                                                <i class="fab fa-facebook text-primary"></i> Facebook
                                            @else
                                                <i class="fas fa-user text-secondary"></i> Local
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Estado:</strong>
                                            @if($user->is_active)
                                                <span class="badge bg-success">Activa</span>
                                            @else
                                                <span class="badge bg-danger">Inactiva</span>
                                            @endif
                                        </p>
                                        <p><strong>Email verificado:</strong>
                                            @if($user->email_verified_at)
                                                <span class="badge bg-success">Sí</span>
                                            @else
                                                <span class="badge bg-warning">No</span>
                                            @endif
                                        </p>
                                        <p><strong>Fecha de registro:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('profile') }}" class="btn btn-primary me-2">
                                        <i class="fas fa-edit me-1"></i>Editar Perfil
                                    </a>
                                    <a href="{{ route('security') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-shield-alt me-1"></i>Configurar Seguridad
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-line me-2"></i>
                                    Actividad Reciente
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="text-success me-3">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Sesión iniciada</div>
                                        <small class="text-muted">{{ $stats['last_login'] }}</small>
                                    </div>
                                </div>

                                @if($user->email_verified_at)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="text-info me-3">
                                        <i class="fas fa-envelope-check"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Email verificado</div>
                                        <small class="text-muted">{{ $user->email_verified_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @endif

                                <div class="d-flex align-items-center">
                                    <div class="text-primary me-3">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Cuenta creada</div>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-bolt me-2"></i>
                                    Acciones Rápidas
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('profile') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-user-edit me-1"></i>Actualizar Perfil
                                    </a>
                                    <a href="{{ route('security') }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-key me-1"></i>Cambiar Contraseña
                                    </a>
                                    @if(!$user->email_verified_at)
                                    <form action="{{ route('verification.send') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-info btn-sm w-100">
                                            <i class="fas fa-envelope me-1"></i>Verificar Email
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- API Information (for developers) -->
                @if(config('app.debug'))
                <div class="row">
                    <div class="col-12">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-code me-2"></i>
                                    Información para Desarrolladores
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">Puedes acceder a tus datos a través de nuestra API REST:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Endpoints disponibles:</h6>
                                        <ul class="list-unstyled">
                                            <li><code>GET /api/user</code> - Tu información</li>
                                            <li><code>GET /api/dashboard/stats</code> - Estadísticas</li>
                                            <li><code>PUT /api/auth/profile</code> - Actualizar perfil</li>
                                            <li><code>POST /api/auth/logout</code> - Cerrar sesión</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Autenticación:</h6>
                                        <p class="small">Usa el token de Sanctum en el header:</p>
                                        <code class="small">Authorization: Bearer {tu_token}</code>
                                        <br><br>
                                        <button class="btn btn-outline-info btn-sm" onclick="testAPI()">
                                            <i class="fas fa-play me-1"></i>Probar API
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
async function testAPI() {
    try {
        const response = await fetch('/api/dashboard/stats', {
            headers: {
                'Authorization': 'Bearer ' + '{{ auth()->user()->createToken("test")->plainTextToken }}',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.ok) {
            const data = await response.json();
            alert('API funcionando correctamente!\n\n' + JSON.stringify(data, null, 2));
        } else {
            alert('Error en la API: ' + response.status);
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

// Auto-refresh stats every 30 seconds
setInterval(() => {
    fetch(window.location.href)
        .then(() => {
            // Optionally refresh stats
            console.log('Stats refreshed');
        })
        .catch(console.error);
}, 30000);
</script>
@endsection
@endsection
