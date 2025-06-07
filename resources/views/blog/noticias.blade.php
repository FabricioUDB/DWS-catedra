@extends('layouts.app')

@section('content')
<style>
    body {
        font-family: 'Nunito', sans-serif;
        background-color: #f8fafc;
    }

    .blog-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
    }

    .blog-header h1 {
        font-size: 3rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .blog-header p {
        font-size: 1.2rem;
        opacity: 0.9;
    }

    .blog-container {
        max-width: 1200px;
        margin: -2rem auto 0;
        padding: 0 2rem;
        position: relative;
        z-index: 10;
    }

    .article-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        transition: transform 0.3s ease;
    }

    .article-card:hover {
        transform: translateY(-5px);
    }

    .article-image {
        height: 250px;
        background: linear-gradient(45deg, #74b9ff, #0984e3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: white;
        opacity: 0.8;
    }

    .article-content {
        padding: 2rem;
    }

    .article-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .article-excerpt {
        color: #6c757d;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .article-meta {
        font-size: 0.9rem;
        color: #868e96;
        margin-bottom: 1.5rem;
    }

    .read-more-btn {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .read-more-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .sidebar-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .sidebar-title {
        color: #667eea;
        font-weight: bold;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #667eea;
    }

    .recent-post {
        padding: 1rem 0;
        border-bottom: 1px solid #eee;
    }

    .recent-post:last-child {
        border-bottom: none;
    }

    .recent-post h6 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .tag {
        background: #f39c12;
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        margin: 0.2rem;
        display: inline-block;
    }
</style>

<div class="blog-header">
    <div class="container">
        <h1>💧 Blog ADACECAM</h1>
        <p>Noticias y actualizaciones sobre servicios de agua en El Salvador</p>
    </div>
</div>

<div class="blog-container">
    <div class="row">
        <!-- Contenido Principal -->
        <div class="col-lg-8">
            <!-- Artículo Principal -->
            <div class="article-card">
                <div class="article-image">🏘️</div>
                <div class="article-content">
                    <h2 class="article-title">Nuevos Sistemas de Pago Digital para Servicios de Agua</h2>
                    <div class="article-meta">
                        <i class="bi bi-calendar3"></i> 15 de junio, 2025 |
                        <i class="bi bi-person"></i> ADACECAM |
                        <i class="bi bi-eye"></i> 1,247 lecturas
                    </div>
                    <p class="article-excerpt">
                        ADACECAM implementa una nueva plataforma digital que permite a los usuarios pagar sus facturas de agua de manera más fácil y segura. El sistema incluye pagos con tarjeta, confirmación instantánea y descarga de comprobantes.
                    </p>
                    <a href="#" class="read-more-btn">Leer más</a>
                </div>
            </div>

            <!-- Más Artículos -->
            <div class="row">
                <div class="col-md-6">
                    <div class="article-card">
                        <div class="article-image" style="height: 200px; font-size: 3rem;">💳</div>
                        <div class="article-content">
                            <h3 class="article-title">Cómo Pagar tu Factura Online</h3>
                            <div class="article-meta">12 de junio, 2025</div>
                            <p class="article-excerpt">
                                Guía paso a paso para realizar pagos de agua a través de nuestra plataforma digital.
                            </p>
                            <a href="#" class="read-more-btn">Leer más</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="article-card">
                        <div class="article-image" style="height: 200px; font-size: 3rem;">🌱</div>
                        <div class="article-content">
                            <h3 class="article-title">Conservación del Agua</h3>
                            <div class="article-meta">10 de junio, 2025</div>
                            <p class="article-excerpt">
                                Tips para reducir el consumo de agua en tu hogar y contribuir al medio ambiente.
                            </p>
                            <a href="#" class="read-more-btn">Leer más</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="article-card">
                        <div class="article-image" style="height: 200px; font-size: 3rem;">🔧</div>
                        <div class="article-content">
                            <h3 class="article-title">Mantenimiento Preventivo</h3>
                            <div class="article-meta">8 de junio, 2025</div>
                            <p class="article-excerpt">
                                Programa de mantenimiento de tuberías y sistemas de distribución de agua.
                            </p>
                            <a href="#" class="read-more-btn">Leer más</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="article-card">
                        <div class="article-image" style="height: 200px; font-size: 3rem;">📊</div>
                        <div class="article-content">
                            <h3 class="article-title">Calidad del Agua 2025</h3>
                            <div class="article-meta">5 de junio, 2025</div>
                            <p class="article-excerpt">
                                Informe anual sobre la calidad del agua suministrada a las comunidades.
                            </p>
                            <a href="#" class="read-more-btn">Leer más</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Entradas Recientes -->
            <div class="sidebar-card">
                <h5 class="sidebar-title">📰 Entradas Recientes</h5>

                <div class="recent-post">
                    <h6>Nuevo horario de atención al cliente</h6>
                    <small class="text-muted">Hace 2 días</small>
                </div>

                <div class="recent-post">
                    <h6>Suspensión programada de agua - Zona Norte</h6>
                    <small class="text-muted">Hace 4 días</small>
                </div>

                <div class="recent-post">
                    <h6>Inauguración de nuevo pozo de agua</h6>
                    <small class="text-muted">Hace 1 semana</small>
                </div>

                <div class="recent-post">
                    <h6>Campaña de concientización hídrica</h6>
                    <small class="text-muted">Hace 2 semanas</small>
                </div>
            </div>

            <!-- Categorías -->
            <div class="sidebar-card">
                <h5 class="sidebar-title">🏷️ Categorías</h5>
                <div>
                    <span class="tag">Noticias (8)</span>
                    <span class="tag">Servicios (12)</span>
                    <span class="tag">Mantenimiento (5)</span>
                    <span class="tag">Calidad (7)</span>
                    <span class="tag">Educación (9)</span>
                    <span class="tag">Medio Ambiente (6)</span>
                </div>
            </div>

            <!-- Contacto -->
            <div class="sidebar-card">
                <h5 class="sidebar-title">📞 Contacto</h5>
                <p><strong>Oficina:</strong><br>
                CA KM 25, Cantón el Carmen<br>
                Cuscatlán, El Salvador</p>

                <p><strong>Email:</strong><br>
                adacecam@gmail.com</p>

                <p><strong>Emergencias:</strong><br>
                📞 2345-6789</p>
            </div>
        </div>
    </div>
</div>

<div style="height: 3rem;"></div> <!-- Espaciado -->

@endsection
