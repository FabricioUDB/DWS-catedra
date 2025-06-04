@extends('layouts.app')

@section('title', 'Crear Cuenta')

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="auth-form">
                    <h2>Crea tu cuenta</h2>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">Tu nombre</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                placeholder="ej: Jose Martinez"
                                value="{{ old('name') }}"
                                required
                                autofocus
                            >
                        </div>

                        <div class="form-group">
                            <label for="email">Correo</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="ej: jose.martinez@gmail.com"
                                value="{{ old('email') }}"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <div class="password-input-container position-relative">
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    placeholder="••••••••"
                                    required
                                    minlength="8"
                                >
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                            </div>
                            <div class="password-strength mt-2">
                                <div class="password-strength-bar">
                                    <div class="password-strength-fill" id="password-strength-fill"></div>
                                </div>
                                <small class="password-strength-text" id="password-strength-text">
                                    Mínimo 8 caracteres
                                </small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmar contraseña</label>
                            <div class="password-input-container position-relative">
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="••••••••"
                                    required
                                >
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                </button>
                            </div>
                            <div class="password-match mt-2">
                                <small class="password-match-text" id="password-match-text"></small>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                Entiendo los términos y la política.
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Registrarse
                            </button>
                        </div>
                    </form>

                    <div class="divider">
                        <span>O regístrate con</span>
                    </div>

                    <div class="social-buttons">
                        <a href="{{ route('auth.google') }}" class="btn-social btn-google">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="{{ route('auth.facebook') }}" class="btn-social btn-facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </div>

                    <div class="auth-links">
                        <span style="color: #666;">¿Tienes una cuenta?</span>
                        <a href="{{ route('login') }}" style="color: var(--adacecam-blue); text-decoration: none; font-weight: 500;">INICIAR SESIÓN</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Alternar visibilidad de contraseña
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Validación de fortaleza de contraseña
    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = [];

        if (password.length >= 8) strength++;
        else feedback.push('mínimo 8 caracteres');

        if (/[a-z]/.test(password)) strength++;
        else feedback.push('una letra minúscula');

        if (/[A-Z]/.test(password)) strength++;
        else feedback.push('una letra mayúscula');

        if (/[0-9]/.test(password)) strength++;
        else feedback.push('un número');

        if (/[^A-Za-z0-9]/.test(password)) strength++;
        else feedback.push('un carácter especial');

        return { strength, feedback };
    }

    // Actualizar medidor de fortaleza
    function updatePasswordStrength() {
        const password = document.getElementById('password').value;
        const strengthFill = document.getElementById('password-strength-fill');
        const strengthText = document.getElementById('password-strength-text');

        if (password.length === 0) {
            strengthFill.style.width = '0%';
            strengthFill.className = 'password-strength-fill';
            strengthText.textContent = 'Mínimo 8 caracteres';
            strengthText.className = 'password-strength-text';
            return;
        }

        const { strength, feedback } = checkPasswordStrength(password);
        const percentage = (strength / 5) * 100;

        strengthFill.style.width = percentage + '%';

        if (strength <= 2) {
            strengthFill.className = 'password-strength-fill weak';
            strengthText.textContent = 'Débil - Necesita: ' + feedback.slice(0, 2).join(', ');
            strengthText.className = 'password-strength-text weak';
        } else if (strength <= 3) {
            strengthFill.className = 'password-strength-fill medium';
            strengthText.textContent = 'Regular - Necesita: ' + feedback.join(', ');
            strengthText.className = 'password-strength-text medium';
        } else if (strength <= 4) {
            strengthFill.className = 'password-strength-fill strong';
            strengthText.textContent = 'Fuerte - Casi perfecto';
            strengthText.className = 'password-strength-text strong';
        } else {
            strengthFill.className = 'password-strength-fill very-strong';
            strengthText.textContent = 'Muy fuerte - Excelente seguridad';
            strengthText.className = 'password-strength-text very-strong';
        }
    }

    // Validación de coincidencia de contraseñas
    function validatePasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const matchText = document.getElementById('password-match-text');

        if (confirmPassword.length === 0) {
            matchText.textContent = '';
            matchText.className = 'password-match-text';
            confirmPassword.setCustomValidity('');
            return;
        }

        if (password === confirmPassword) {
            matchText.textContent = '✓ Las contraseñas coinciden';
            matchText.className = 'password-match-text match';
            document.getElementById('password_confirmation').setCustomValidity('');
        } else {
            matchText.textContent = '✗ Las contraseñas no coinciden';
            matchText.className = 'password-match-text no-match';
            document.getElementById('password_confirmation').setCustomValidity('Las contraseñas no coinciden');
        }
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Actualizar navegación activa
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            if (link.getAttribute('href') === '/register') {
                link.classList.add('active');
                if (link.textContent.trim() === 'Iniciar sesión') {
                    link.textContent = 'Crear tu cuenta';
                }
            } else {
                link.classList.remove('active');
            }
        });

        // Validaciones de contraseña
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('password_confirmation');

        passwordField.addEventListener('input', function() {
            updatePasswordStrength();
            if (confirmPasswordField.value.length > 0) {
                validatePasswordMatch();
            }
        });

        confirmPasswordField.addEventListener('input', validatePasswordMatch);

        // Validación del formulario
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const password = passwordField.value;
            const { strength } = checkPasswordStrength(password);

            if (strength < 3) {
                e.preventDefault();
                alert('Por favor, utiliza una contraseña más fuerte para mayor seguridad.');
                return false;
            }

            if (password !== confirmPasswordField.value) {
                e.preventDefault();
                alert('Las contraseñas no coinciden.');
                return false;
            }
        });
    });
</script>
@endsection
