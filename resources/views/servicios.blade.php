{{-- Redirección si no hay sesión --}}
@if(!session('usuario_id'))
    <script>window.location = "{{ route('login') }}";</script>
@endif
@extends('layouts.app')

@section('title', 'Servicios')

@push('styles')
<style>
    .services-hero {
        background: linear-gradient(rgba(11, 28, 43, 0.9), rgba(11, 28, 43, 0.8));
        background-size: cover;
        background-position: center;
        color: white;
        padding: 5rem 0;
    }
    .service-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .service-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: #0B5ED7;
    }
    .feature-box {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 2rem;
        height: 100%;
    }
    .tab-content {
        padding: 2rem 0;
    }
    .nav-tabs .nav-link.active {
        font-weight: bold;
        border-bottom: 3px solid #0B1C2B;
    }
</style>
@endpush

@section('content')

<!-- Hero Section de Servicios -->
<section class="services-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">Nuestros Servicios</h1>
                <p class="lead">Descubre todo lo que nuestra biblioteca puede ofrecerte para apoyar tu aprendizaje e investigación</p>
            </div>
        </div>
    </div>
</section>

<!-- Servicios Principales SOLO PRÉSTAMO DE LIBROS FÍSICOS -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Préstamo de Libros</h2>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card service-card h-100 shadow-sm">
                    <img src="{{ asset('img/LocalB/PL.jpg') }}" class="card-img-top" alt="Préstamo de libros">
                    <div class="card-body">
                        <div class="service-icon text-center">
                            <i class="bi bi-book"></i>
                        </div>
                        <h3 class="card-title text-center">Préstamo de Libros Físicos</h3>
                        <p class="card-text text-center">
                            Solicita libros físicos de nuestro catálogo por 15 días, con opción de renovación y reserva anticipada.
                        </p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i>Prestamos anticipados</li>
                            <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i>Hasta 5 días de prestamo</li>
                            <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i>Hasta 3 Libros simultaneamente</li>
                        </ul>
                        <div class="d-grid">
                            <a href="{{ route('libros.index') }}" class="btn btn-primary">Más información</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Horarios y Contacto -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Horario de Atención</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Lunes a Viernes
                                <span class="badge bg-light text-dark">8:00 am - 8:00 pm</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Sábados
                                <span class="badge bg-light text-dark">9:00 am - 2:00 pm</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Domingos y Feriados
                                <span class="badge bg-light text-dark">Cerrado</span>
                            </li>
                        </ul>
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle-fill me-2"></i> El servicio de recursos digitales está disponible 24/7 a través de nuestra plataforma.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Contacto y Soporte</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3"><i class="bi bi-telephone-fill text-primary me-2"></i> <strong>Teléfono:</strong> +51 956354722</li>
                            <li class="mb-3"><i class="bi bi-envelope-fill text-primary me-2"></i> <strong>Email:</strong> biblioteca@tecsup.edu.pe</li>
                            <li class="mb-3"><i class="bi bi-geo-alt-fill text-primary me-2"></i> <strong>Ubicación:</strong> Pabellon D4</li>
                            <li class="mb-3"><i class="bi bi-headset text-primary me-2"></i> <strong>Soporte técnico:</strong> soporte.biblioteca@tecsup.edu.pe</li>
                        </ul>
                        <div class="d-grid gap-2">
                            <a href="{{ route('contacto') }}#frecuentes" class="btn btn-primary">
                                <i class="bi bi-question-circle-fill me-2"></i> Preguntas Frecuentes
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
