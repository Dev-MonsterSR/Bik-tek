{{-- Redirección si no hay sesión --}}
@if(!session('usuario_id'))
    <script>window.location = "{{ route('login') }}";</script>
@endif
@extends('layouts.app')

@section('title', 'Contacto')

@push('styles')
<style>
    body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; }
    .contact-hero {
        background: linear-gradient(rgba(11, 28, 43, 0.9), rgba(11, 28, 43, 0.8)), url("{{ asset('img/contacto-bg.jpg') }}");
        background-size: cover;
        background-position: center;
        color: white;
        padding: 6rem 0;
    }
    .contact-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 10px;
        overflow: hidden;
        height: 100%;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
    .contact-icon { font-size: 2rem; color: #0b5ed7; margin-bottom: 1rem; }
    .form-control:focus {
        border-color: #0b1c2b;
        box-shadow: 0 0 0 0.25rem rgba(11, 28, 43, 0.25);
    }
    .map-wrapper {
        position: relative;
        padding-bottom: 75%;
        height: 0;
        overflow: hidden;
        border-radius: 10px;
    }
    .map-wrapper iframe {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%; border: none;
    }
    .social-icons a {
        color: white; font-size: 1.2rem; margin-right: 15px; transition: color 0.3s;
    }
    .social-icons a:hover { color: #0b5ed7; }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="contact-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3">Contáctanos</h1>
                <p class="lead">
                    Biblioteca Tecsup Trujillo - Estamos para ayudarte con tus consultas sobre préstamos y servicios.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Información de Contacto -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Información de Contacto</h2>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card contact-card">
                    <div class="card-body text-center p-4">
                        <div class="contact-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <h3 class="card-title mb-3">Visítanos</h3>
                        <p class="card-text mb-4">
                            Tecsup Norte / Campus Trujillo el Golf<br />
                            Trujillo, Perú<br /><br />
                            <strong>Teléfono:</strong> +51 956354722 <br />
                            <strong>Email:</strong> biblioteca.trujillo@tecsup.edu.pe
                        </p>
                        <a href="#ubicacion" class="btn btn-primary px-4">Ver en mapa</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mapa y Horario -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Ubicación y Horarios</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="map-wrapper" id="ubicacion" style="padding-bottom: 60%; height: 400px;">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.668073289889!2d-79.0215436846222!3d-8.11188999416844!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91ad3d2e7e7e7e7f%3A0x7e7e7e7e7e7e7e7e!2sTECSUP%20Trujillo!5e0!3m2!1ses-419!2spe!4v1680846778861!5m2!1ses-419!2spe" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <h4 class="mb-3"><i class="bi bi-clock-fill text-primary me-2"></i>Horario de Atención</h4>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Lunes a Viernes
                                        <span class="badge bg-primary rounded-pill">8:00 am - 6:00 pm</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Sábados
                                        <span class="badge bg-primary rounded-pill">9:00 am - 2:00 pm</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Domingos y Feriados
                                        <span class="badge bg-secondary rounded-pill">Cerrado</span>
                                    </li>
                                </ul>
                                <div class="alert alert-info mt-3">
                                    <i class="bi bi-info-circle-fill me-2"></i> Para consultas urgentes fuera de horario, puedes enviar un email.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Preguntas Frecuentes -->
<section class="py-5">
    <div class="container">
        <h2 id="frecuentes" class="text-center fw-bold mb-5">Preguntas Frecuentes</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">


                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                ¿Cuántos libros puedo llevar en préstamo?
                            </button>
                        </h3>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Puedes llevar hasta 3 libros físicos simultáneamente por 5 días. La renovación se realiza en la biblioteca, siempre que no haya prestamos pendientes para esos ejemplares.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                ¿Qué debo hacer si pierdo un libro prestado?
                            </button>
                        </h3>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Debes comunicarte inmediatamente con el personal de la biblioteca. Tendrás que reponer el libro o abonar el costo de reposición más una multa administrativa.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                ¿Cómo consulto el catálogo de libros disponibles?
                            </button>
                        </h3>
                        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Puedes consultar el catálogo completo desde el sistema de biblioteca. Solo necesitas iniciar sesión  y podrás buscar libros por título, autor o categoría.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
                                ¿Qué sucede si no devuelvo un libro a tiempo?
                            </button>
                        </h3>
                        <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                El sistema genera automáticamente una sanción por retraso. No podrás realizar nuevos préstamos hasta que regularices tu situación.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix">
                                ¿Puedo renovar un préstamo desde el sistema?
                            </button>
                        </h3>
                        <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                No, las renovaciones deben hacerse presencialmente en la biblioteca. El personal verificará que no haya otros usuarios esperando el mismo libro y procederá con la renovación si es posible.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven">
                                ¿Cómo verifico el estado de mis préstamos actuales?
                            </button>
                        </h3>
                        <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                En tu perfil del sistema encontrarás la sección "Historial" donde podrás ver todos los libros que tienes prestados, las fechas de vencimiento y el estado de cada préstamo.
                            </div>
                        </div>
                    </div>


                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine">
                                ¿Puedo reservar un libro que está prestado?
                            </button>
                        </h3>
                        <div id="collapseNine" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Actualmente el sistema no cuenta con función de reservas. Te recomendamos consultar periódicamente la disponibilidad del libro o solicitar al personal que te notifique cuando esté disponible.
                            </div>
                        </div>
                    </div>



                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEleven">
                                ¿El sistema guarda mi historial de préstamos?
                            </button>
                        </h3>
                        <div id="collapseEleven" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Sí, el sistema mantiene un registro completo de todos tus préstamos anteriores. Puedes consultar tu historial en la sección "Historial" de tu perfil de usuario.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen">
                                ¿Cómo puedo reportar un error en el sistema?
                            </button>
                        </h3>
                        <div id="collapseTen" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Si encuentras algún error o problema técnico en el sistema, puedes reportarlo directamente al personal de la biblioteca o enviar un email a biblioteca.trujillo@tecsup.edu.pe con los detalles del problema.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <p>
                ¿No encontraste tu respuesta?
                <a href="mailto:biblioteca.trujillo@tecsup.edu.pe" class="text-primary">Escríbenos directamente</a>
            </p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Script para mejorar la experiencia de usuario con el accordion
    document.addEventListener('DOMContentLoaded', function() {
        // Agregar efectos suaves al accordion
        const accordionButtons = document.querySelectorAll('.accordion-button');
        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Scroll suave hacia la pregunta cuando se expande
                setTimeout(() => {
                    this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 300);
            });
        });
    });
</script>
@endpush
