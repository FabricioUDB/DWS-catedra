@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="auth-form">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">Correo</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="Introduce tu correo"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Introduce tu contraseña"
                                required
                            >
                        </div>

                        <div class="form-check d-flex align-items-center mb-3">
                            <input type="checkbox" class="form-check-input me-2" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Recordarme
                            </label>
                        </div>

                        <div class="forgot-password">
                            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">
                                Iniciar sesión
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
                        <span style="color: #666;">¿No tienes una cuenta?</span>
                        <a href="{{ route('register') }}" style="color: var(--adacecam-blue); text-decoration: none; font-weight: 500;">CREAR CUENTA</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Actualizar navegación activa
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            if (link.getAttribute('href') === '/login' || link.getAttribute('href') === '/') {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    });
</script>
@endsection
