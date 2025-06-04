@extends('layouts.app')

@section('title', 'Mi Perfil')

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
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link active" href="{{ route('profile') }}">
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
                        <h1 class="h3 mb-0">Mi Perfil</h1>
                        <p class="text-muted mb-0">Gestiona tu información personal</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
                    </a>
                </div>

                <div class="row">
                    <!-- Profile Information -->
                    <div class="col-lg-8 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-user me-2"></i>
                                    Información Personal
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('profile.update') }}">
                                    @csrf

                                    <!-- Name Field -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            <i class="fas fa-user me-1"></i>
                                            Nombre Completo
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="name"
                                            name="name"
                                            value="{{ old('name', $user->name) }}"
                                            required
                                            maxlength="255"
                                        >
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email Field -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-1"></i>
                                            Correo Electrónico
                                        </label>
                                        <input
                                            type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            id="email"
                                            name="email"
                                            value="{{ old('email', $user->email) }}"
                                            required
                                        >
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if(!$user->email_verified_at)
                                        <div class="form-text text-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Tu email no está verificado.
                                            <form action="{{ route('verification.send') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-link p-0 text-warning">
                                                    Enviar verificación
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                        <div class="form-text text-success">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Email verificado el {{ $user->email_verified_at->format('d/m/Y') }}
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Save Button -->
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>
                                            Guardar Cambios
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary">
                                            <i class="fas fa-undo me-1"></i>
                                            Deshacer
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Summary -->
                    <div class="col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Resumen de Cuenta
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                                <h6 class="mb-1">{{ $user->name }}</h6>
                                <p class="text-muted mb-2">{{ $user->email }}</p>

                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="border-end">
                                            <div class="h6 mb-1">Proveedor</div>
                                            @if($user->provider === 'google')
                                                <span class="badge bg-danger">
                                                    <i class="fab fa-google me-1"></i>Google
                                                </span>
                                            @elseif($user->provider === 'facebook')
                                                <span class="badge bg-primary">
                                                    <i class="fab fa-facebook me-1"></i>Facebook
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-user me-1"></i>Local
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="h6 mb-1">Estado</div>
                                        @if($user->is_active)
                                            <span class="badge bg-success">Activa</span>
                                        @else
                                            <span class="badge bg-danger">Inactiva</span>
                                        @endif
                                    </div>
                                </div>

                                <hr>

                                <div class="small text-muted">
                                    <div><strong>Miembro desde:</strong> {{ $user->created_at->format('d/m/Y') }}</div>
                                    <div><strong>Última actualización:</strong> {{ $user->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Connected Accounts -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-link me-2"></i>
                                    Cuentas Conectadas
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Google Account -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fab fa-google text-danger me-2"></i>
                                        <span>Google</span>
                                    </div>
                                    <div>
                                        @if($user->google_id)
                                            <span class="badge bg-success me-2">Conectada</span>
                                            <form action="{{ route('auth.unlink.google') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Estás seguro de desvincular Google?')">
                                                    Desvincular
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('auth.google') }}" class="btn btn-outline-danger btn-sm">
                                                Conectar
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <!-- Facebook Account -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="fab fa-facebook text-primary me-2"></i>
                                        <span>Facebook</span>
                                    </div>
                                    <div>
                                        @if($user->facebook_id)
                                            <span class="badge bg-success me-2">Conectada</span>
                                            <form action="{{ route('auth.unlink.facebook') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary btn-sm" onclick="return confirm('¿Estás seguro de desvincular Facebook?')">
                                                    Desvincular
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('auth.facebook') }}" class="btn btn-outline-primary btn-sm">
                                                Conectar
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Zona de Peligro
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="text-danger">Desactivar Cuenta</h6>
                                        <p class="text-muted mb-0">
                                            Una vez que desactives tu cuenta, todos tus datos serán permanentemente eliminados.
                                            Antes de desactivar tu cuenta, descarga cualquier dato o información que desees conservar.
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <a href="{{ route('security') }}" class="btn btn-outline-danger">
                                            <i class="fas fa-user-times me-1"></i>
                                            Desactivar Cuenta
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');

    // Real-time validation
    nameInput.addEventListener('input', function() {
        if (this.value.length < 2) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });

    emailInput.addEventListener('input', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(this.value)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });

    // Confirm before disconnecting social accounts
    document.querySelectorAll('form[action*="unlink"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const provider = this.action.includes('google') ? 'Google' : 'Facebook';
            if (!confirm(`¿Estás seguro de que quieres desvincular tu cuenta de ${provider}?`)) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection
@endsection
