@extends('layouts.app')

@section('title', 'Seguridad')

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
                        <a class="nav-link" href="{{ route('profile') }}">
                            <i class="fas fa-user me-2"></i>Perfil
                        </a>
                        <a class="nav-link active" href="{{ route('security') }}">
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
                        <h1 class="h3 mb-0">Configuración de Seguridad</h1>
                        <p class="text-muted mb-0">Protege tu cuenta y gestiona tu seguridad</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
                    </a>
                </div>

                <div class="row">
                    <!-- Password Management -->
                    <div class="col-lg-8 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-key me-2"></i>
                                    Gestión de Contraseña
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($securityInfo['has_password'])
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Tu cuenta tiene una contraseña configurada
                                    </div>

                                    <form method="POST" action="{{ route('security.password') }}" id="passwordForm">
                                        @csrf

                                        <!-- Current Password -->
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">
                                                <i class="fas fa-lock me-1"></i>
                                                Contraseña Actual
                                            </label>
                                            <div class="input-group">
                                                <input
                                                    type="password"
                                                    class="form-control @error('current_password') is-invalid @enderror"
                                                    id="current_password"
                                                    name="current_password"
                                                    required
                                                >
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Tu cuenta no tiene contraseña. Establece una para mayor seguridad.
                                    </div>

                                    <form method="POST" action="{{ route('security.password') }}" id="passwordForm">
                                        @csrf
                                @endif

                                        <!-- New Password -->
                                        <div class="mb-3">
                                            <label for="password" class="form-label">
                                                <i class="fas fa-key me-1"></i>
                                                {{ $securityInfo['has_password'] ? 'Nueva Contraseña' : 'Contraseña' }}
                                            </label>
                                            <div class="input-group">
                                                <input
                                                    type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password"
                                                    name="password"
                                                    required
                                                    minlength="8"
                                                >
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%"></div>
                                            </div>
                                            <small class="text-muted" id="passwordStrengthText">Introduce una contraseña</small>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">
                                                <i class="fas fa-lock me-1"></i>
                                                Confirmar Contraseña
                                            </label>
                                            <div class="input-group">
                                                <input
                                                    type="password"
                                                    class="form-control"
                                                    id="password_confirmation"
                                                    name="password_confirmation"
                                                    required
                                                    minlength="8"
                                                >
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted" id="passwordMatchText"></small>
                                        </div>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>
                                            {{ $securityInfo['has_password'] ? 'Actualizar Contraseña' : 'Establecer Contraseña' }}
                                        </button>
                                    </form>
                            </div>
                        </div>

                        <!-- Active Sessions -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-laptop me-2"></i>
                                    Sesiones Activas
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Tienes {{ $securityInfo['active_sessions'] }} {{ $securityInfo['active_sessions'] == 1 ? 'sesión activa' : 'sesiones activas' }}.</p>

                                <div class="d-flex justify-content-between align-items-center p-3 border rounded mb-3">
                                    <div>
                                        <div class="fw-semibold">
                                            <i class="fas fa-desktop me-2 text-success"></i>
                                            Sesión Actual
                                        </div>
                                        <small class="text-muted">{{ request()->ip() }} • {{ request()->userAgent() }}</small>
                                    </div>
                                    <span class="badge bg-success">Activa</span>
                                </div>

                                @if($securityInfo['active_sessions'] > 1)
                                <form method="POST" action="{{ route('security.revoke-tokens') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-warning" onclick="return confirm('¿Estás seguro de cerrar todas las otras sesiones?')">
                                        <i class="fas fa-sign-out-alt me-1"></i>
                                        Cerrar Otras Sesiones
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Security Summary -->
                    <div class="col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    Estado de Seguridad
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Password Status -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Contraseña</span>
                                    @if($securityInfo['has_password'])
                                        <span class="badge bg-success">Configurada</span>
                                    @else
                                        <span class="badge bg-warning">No configurada</span>
                                    @endif
                                </div>

                                <!-- Email Verification -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Email verificado</span>
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">Verificado</span>
                                    @else
                                        <span class="badge bg-warning">Pendiente</span>
                                    @endif
                                </div>

                                <!-- Google Account -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Google</span>
                                    @if($securityInfo['has_google'])
                                        <span class="badge bg-success">Conectado</span>
                                    @else
                                        <span class="badge bg-secondary">No conectado</span>
                                    @endif
                                </div>

                                <!-- Facebook Account -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Facebook</span>
                                    @if($securityInfo['has_facebook'])
                                        <span class="badge bg-success">Conectado</span>
                                    @else
                                        <span class="badge bg-secondary">No conectado</span>
                                    @endif
                                </div>

                                <!-- Two Factor -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Autenticación 2FA</span>
                                    @if($securityInfo['two_factor_enabled'])
                                        <span class="badge bg-success">Habilitada</span>
                                    @else
                                        <span class="badge bg-secondary">Deshabilitada</span>
                                    @endif
                                </div>

                                <hr>

                                <!-- Security Score -->
                                @php
                                    $score = 0;
                                    if($securityInfo['has_password']) $score += 25;
                                    if($user->email_verified_at) $score += 25;
                                    if($securityInfo['has_google'] || $securityInfo['has_facebook']) $score += 25;
                                    if($securityInfo['two_factor_enabled']) $score += 25;
                                @endphp

                                <div class="text-center">
                                    <div class="h4 mb-1">{{ $score }}%</div>
                                    <div class="text-muted mb-2">Puntuación de Seguridad</div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar @if($score >= 75) bg-success @elseif($score >= 50) bg-warning @else bg-danger @endif"
                                             role="progressbar" style="width: {{ $score }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tips -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    Consejos de Seguridad
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Usa contraseñas únicas y fuertes
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Verifica tu dirección de email
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Conecta cuentas sociales para respaldo
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Revisa tus sesiones regularmente
                                    </li>
                                </ul>
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
                                        <p class="text-muted mb-3">
                                            Una vez que desactives tu cuenta, perderás acceso a todos los servicios.
                                            Esta acción no se puede deshacer fácilmente.
                                        </p>

                                        <form method="POST" action="{{ route('security.deactivate') }}" id="deactivateForm">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="confirmation" class="form-label">
                                                    Para confirmar, escribe <strong>DEACTIVATE</strong> en el campo:
                                                </label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="confirmation"
                                                    name="confirmation"
                                                    placeholder="DEACTIVATE"
                                                    required
                                                >
                                            </div>
                                            <button type="submit" class="btn btn-danger" disabled id="deactivateBtn">
                                                <i class="fas fa-user-times me-1"></i>
                                                Desactivar Mi Cuenta
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="text-danger">
                                            <i class="fas fa-exclamation-triangle" style="font-size: 3rem;"></i>
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
</div>

@section('scripts')
<script>
// Password toggle functionality
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = event.target.closest('button').querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Password strength checker
    const password = document.getElementById('password');
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('passwordStrengthText');

    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = [];

        if (password.length >= 8) strength += 1;
        else feedback.push('mínimo 8 caracteres');

        if (password.match(/[a-z]/)) strength += 1;
        else feedback.push('una minúscula');

        if (password.match(/[A-Z]/)) strength += 1;
        else feedback.push('una mayúscula');

        if (password.match(/[0-9]/)) strength += 1;
        else feedback.push('un número');

        if (password.match(/[^a-zA-Z0-9]/)) strength += 1;
        else feedback.push('un símbolo');

        return { strength, feedback };
    }

    if (password) {
        password.addEventListener('input', function() {
            const { strength, feedback } = checkPasswordStrength(this.value);
            const percentage = (strength / 5) * 100;

            strengthBar.style.width = percentage + '%';

            if (strength <= 2) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Débil - Faltan: ' + feedback.join(', ');
            } else if (strength <= 3) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Regular - Faltan: ' + feedback.join(', ');
            } else if (strength <= 4) {
                strengthBar.className = 'progress-bar bg-info';
                strengthText.textContent = 'Buena - Faltan: ' + feedback.join(', ');
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Excelente';
            }
        });
    }

    // Password confirmation checker
    const passwordConfirmation = document.getElementById('password_confirmation');
    const matchText = document.getElementById('passwordMatchText');

    function checkPasswordMatch() {
        if (!passwordConfirmation || !password) return;

        if (passwordConfirmation.value === '') {
            matchText.textContent = '';
            passwordConfirmation.classList.remove('is-valid', 'is-invalid');
            return;
        }

        if (password.value === passwordConfirmation.value) {
            matchText.textContent = '✓ Las contraseñas coinciden';
            matchText.className = 'text-success';
            passwordConfirmation.classList.remove('is-invalid');
            passwordConfirmation.classList.add('is-valid');
        } else {
            matchText.textContent = '✗ Las contraseñas no coinciden';
            matchText.className = 'text-danger';
            passwordConfirmation.classList.remove('is-valid');
            passwordConfirmation.classList.add('is-invalid');
        }
    }

    if (password && passwordConfirmation) {
        password.addEventListener('input', checkPasswordMatch);
        passwordConfirmation.addEventListener('input', checkPasswordMatch);
    }

    // Deactivate account confirmation
    const confirmationInput = document.getElementById('confirmation');
    const deactivateBtn = document.getElementById('deactivateBtn');

    if (confirmationInput && deactivateBtn) {
        confirmationInput.addEventListener('input', function() {
            if (this.value === 'DEACTIVATE') {
                deactivateBtn.disabled = false;
                deactivateBtn.classList.remove('btn-secondary');
                deactivateBtn.classList.add('btn-danger');
            } else {
                deactivateBtn.disabled = true;
                deactivateBtn.classList.remove('btn-danger');
                deactivateBtn.classList.add('btn-secondary');
            }
        });

        document.getElementById('deactivateForm').addEventListener('submit', function(e) {
            if (!confirm('¿ESTÁS COMPLETAMENTE SEGURO? Esta acción desactivará tu cuenta y cerrarás sesión inmediatamente.')) {
                e.preventDefault();
            }
        });
    }

    // Auto-save form data
    const form = document.getElementById('passwordForm');
    if (form) {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...';
        });
    }
});
</script>
@endsection
@endsection
