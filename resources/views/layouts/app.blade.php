<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ADACECAM - @yield('title', 'Sistema')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --adacecam-blue: #4A90E2;
            --adacecam-dark-blue: #3A7BD5;
            --adacecam-light-blue: #5BA0F2;
            --adacecam-gray: #6c757d;
            --adacecam-light-gray: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        /* Header azul */
        .top-header {
            background: linear-gradient(135deg, var(--adacecam-blue) 0%, var(--adacecam-dark-blue) 100%);
            color: white;
            padding: 8px 0;
            font-size: 14px;
        }

        .top-header input {
            background: rgba(255,255,255,0.2);
            border: none;
            border-radius: 20px;
            padding: 5px 15px;
            color: white;
            width: 300px;
        }

        .top-header input::placeholder {
            color: rgba(255,255,255,0.8);
        }

        .top-header .search-btn {
            background: none;
            border: none;
            color: white;
            margin-left: -35px;
        }

        .top-header .header-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .top-header .header-icons i {
            font-size: 18px;
            cursor: pointer;
        }

        /* Navegación principal */
        .main-nav {
            background: white;
            padding: 15px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .main-nav .navbar-nav {
            margin-left: auto;
        }

        .main-nav .nav-link {
            color: var(--adacecam-gray);
            font-weight: 500;
            margin: 0 15px;
            transition: color 0.3s;
        }

        .main-nav .nav-link:hover,
        .main-nav .nav-link.active {
            color: var(--adacecam-blue);
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--adacecam-blue);
            font-weight: bold;
            font-size: 18px;
        }

        .logo i {
            background: var(--adacecam-blue);
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        /* Formularios */
        .auth-container {
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }

        .auth-form {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .auth-form h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            color: #555;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--adacecam-blue);
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
        }

        .btn-primary {
            background: var(--adacecam-blue);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: var(--adacecam-dark-blue);
            transform: translateY(-1px);
        }

        /* Botones sociales */
        .social-buttons {
            display: flex;
            gap: 15px;
            margin: 20px 0;
        }

        .btn-social {
            flex: 1;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            text-decoration: none;
            color: #666;
        }

        .btn-social:hover {
            border-color: var(--adacecam-blue);
            color: var(--adacecam-blue);
            transform: translateY(-1px);
        }

        .btn-social i {
            font-size: 20px;
        }

        .btn-google i {
            color: #4285f4;
        }

        .btn-facebook i {
            color: #1877f2;
        }

        /* Enlaces */
        .auth-links {
            text-align: center;
            margin-top: 20px;
        }

        .auth-links a {
            color: var(--adacecam-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }

        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }

        .forgot-password a {
            color: var(--adacecam-gray);
            font-size: 14px;
            text-decoration: none;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
        }

        .footer h6 {
            color: white;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .footer ul {
            list-style: none;
            padding: 0;
        }

        .footer ul li {
            margin-bottom: 8px;
        }

        .footer ul li a {
            color: #bdc3c7;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer ul li a:hover {
            color: white;
        }

        .footer .contact-info {
            color: #bdc3c7;
        }

        .footer .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .footer .social-icons a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }

        .footer .social-icons .facebook { background: #1877f2; }
        .footer .social-icons .instagram { background: #e4405f; }
        .footer .social-icons .whatsapp { background: #25d366; }

        .footer .social-icons a:hover {
            transform: translateY(-2px);
        }

        /* Alertas */
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        /* Checkbox personalizado */
        .form-check-input:checked {
            background-color: var(--adacecam-blue);
            border-color: var(--adacecam-blue);
        }

        .form-check-label {
            font-size: 14px;
            color: #666;
        }

        /* Password input with toggle */
        .password-input-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 10;
        }

        .password-toggle:hover {
            color: var(--adacecam-blue);
        }

        /* Password strength meter */
        .password-strength-bar {
            width: 100%;
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            overflow: hidden;
        }

        .password-strength-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            background: #e9ecef;
        }

        .password-strength-fill.weak {
            background: #dc3545;
        }

        .password-strength-fill.medium {
            background: #ffc107;
        }

        .password-strength-fill.strong {
            background: #17a2b8;
        }

        .password-strength-fill.very-strong {
            background: #28a745;
        }

        .password-strength-text {
            font-size: 12px;
            color: #6c757d;
        }

        .password-strength-text.weak {
            color: #dc3545;
        }

        .password-strength-text.medium {
            color: #ffc107;
        }

        .password-strength-text.strong {
            color: #17a2b8;
        }

        .password-strength-text.very-strong {
            color: #28a745;
        }

        /* Password match indicator */
        .password-match-text {
            font-size: 12px;
            color: #6c757d;
        }

        .password-match-text.match {
            color: #28a745;
        }

        .password-match-text.no-match {
            color: #dc3545;
        }

        /* Separador */
        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
            color: #666;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
        }

        .divider span {
            background: white;
            padding: 0 15px;
        }
    </style>
</head>
<body>
    <!-- Header azul superior -->
    <div class="top-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <input type="text" placeholder="Buscar productos, servicios, etc..." class="form-control">
                        <button class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="header-icons justify-content-end">
                        <i class="fas fa-user"></i>
                        <i class="fas fa-heart"></i>
                        <span>$0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Navegación principal -->
    <nav class="main-nav">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <a href="/" class="logo">
                        <i class="fas fa-tint"></i>
                        <span>ADACECAM</span>
                    </a>
                </div>
                <div class="col-md-9">
                    <ul class="navbar-nav d-flex flex-row justify-content-end">
                        <li class="nav-item">
                            <a class="nav-link @if(Request::is('login') || Request::is('/')) active @endif" href="/login">Iniciar sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#funciones">Funciones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#equipos">Nuestros equipos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#blog">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contacto">Contáctanos</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Alertas -->
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger">{{ session('error') }}</div>
        </div>
    @endif

    <!-- Contenido principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-tint" style="background: var(--adacecam-blue); color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; margin-right: 10px;"></i>
                        <span style="color: white; font-weight: bold;">adacecam</span>
                    </div>
                    <p style="color: #bdc3c7; font-size: 14px;">Enfoque en calidad y pureza del agua.</p>
                </div>
                <div class="col-md-3">
                    <h6>Contáctanos</h6>
                    <div class="contact-info">
                        <p><i class="fas fa-map-marker-alt"></i> CA KM 25, Canton el Carmen, Cuscatlán</p>
                        <p><i class="fas fa-envelope"></i> adacecamcarmen@gmail.com</p>
                        <p><i class="fas fa-phone"></i> +503 8001-9630</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <h6>Servicios</h6>
                    <ul>
                        <li><a href="#">Pago en línea</a></li>
                        <li><a href="#">Solicitud de servicios</a></li>
                        <li><a href="#">Reportar problemas</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Cooperativa</h6>
                    <ul>
                        <li><a href="#">Funciones</a></li>
                        <li><a href="#">Nuestros equipos</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Contáctanos</a></li>
                    </ul>
                    <div class="social-icons">
                        <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="whatsapp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                    <p style="color: #bdc3c7; font-size: 12px; margin-top: 10px;">Búscanos en nuestras redes sociales</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
