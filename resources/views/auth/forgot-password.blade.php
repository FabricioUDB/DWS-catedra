@extends('layouts.app')

@section('title', 'Recuperar Contraseña')

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="auth-form">
                    <h2>Recuperar Contraseña</h2>
                    <p class="text-center text-muted mb-4">
                        Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">Correo electrónico</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="Introduce tu correo electrónico"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>
                                Enviar enlace de recuperación
                            </button>
                        </div>
                    </form>

                    <div class="auth-links">
                        <a href="{{ route('login') }}" style="color: var(--adacecam-blue); text-decoration: none; font-weight: 500;">
                            <i class="fas fa-arrow-left me-2"></i>
                            Volver al inicio de sesión
                        </a>
                    </div>

                    <div class="divider">
                        <span>¿No tienes cuenta?</span>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">
                            Crear cuenta nueva
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Actualizar navegación activa
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.classList.remove('active');
        });

        // Auto-focus en el campo de email
        document.getElementById('email').focus();

        // Validación del email en tiempo real
        const emailInput = document.getElementById('email');
        emailInput.addEventListener('input', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email.length > 0 && !emailRegex.test(email)) {
                this.setCustomValidity('Por favor, introduce un correo electrónico válido');
            } else {
                this.setCustomValidity('');
            }
        });
    });
</script>
@endsection
