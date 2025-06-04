@extends('layouts.app')

@section('title', 'Página no encontrada')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center py-5">
            <div class="mb-4">
                <i class="fas fa-exclamation-triangle text-warning" style="font-size: 5rem;"></i>
            </div>

            <h1 class="display-1 fw-bold text-primary">404</h1>
            <h2 class="h4 mb-3">¡Oops! Página no encontrada</h2>
            <p class="lead text-muted mb-4">
                La página que estás buscando no existe o ha sido movida.
            </p>

            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i>Ir al Inicio
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </a>
                @endauth

                <button onclick="history.back()" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver Atrás
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
