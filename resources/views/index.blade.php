{{-- Redirección si no hay sesión --}}
@if(!session('usuario_id'))
    <script>window.location = "{{ route('login') }}";</script>
@endif
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bik Tek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"/>
    <style>
        .hero-section {
            background: linear-gradient(rgba(11, 28, 43, 0.9), rgba(11, 28, 43, 0.9)), url("img/biblioteca-hero.jpg");
            background-size: cover;
            background-position: center;
            color: white;
            padding: 6rem 0;
        }
        .btn-logout {
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.3);
        background-color: transparent;
        padding: 6px 16px;
        border-radius: 20px;
        transition: all 0.3s ease-in-out;
        font-weight: 500;
        }

        .btn-logout:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
            transform: scale(1.05);
        }
        .welcome-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 8px rgba(11,28,43,0.18);
        }
        .welcome-user {
            color: #0B5ED7;
            font-weight: 800;
            font-size: 2.5rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px rgba(11,28,43,0.18);
        }
        .book-card { transition: transform 0.3s; }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .feature-icon { font-size: 2.5rem; color: #0b5ed7; }
        .newsletter { background-color: #f8f9fa; }
        .card-img-top {
            width: 100%; height: 450px; object-fit: cover; object-position: center;
            border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #0b1c2b">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('inicio') }}">
                <img src="{{ asset('/img/LocalB/logo.jpg') }}" alt="Logo Biblioteca" width="40" height="40" class="me-2"/>
                <span class="fw-bold">Bik Tek</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="{{ route('inicio') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('libros.index') }}">Catálogo</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('servicios') }}">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contacto') }}">Contacto</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                @if(session('usuario_id'))
                    <a href="{{ route('usuario.historial') }}" class="btn btn-outline-light me-2">
                        <i class="bi bi-clock-history me-1"></i> Historial
                    </a>
                @endif
                @if(session('usuario_id') || session('admin_id'))
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-logout">
                            <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
                        </button>
                    </form>
                @endif
            </div>>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <div class="welcome-title mb-2">
                Bienvenido,
                <span class="welcome-user">
                    {{ \App\Models\Usuario::find(session('usuario_id'))->nombre ?? 'Usuario' }}
                </span>
            </div>
            <h1 class="display-4 fw-bold mb-4">Descubre un mundo de conocimiento</h1>
            <p class="lead mb-5">Accede al préstamo de muchos recursos académicos y literarios a tu disposición</p>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="{{ route('libros.index') }}" method="GET" class="input-group mb-3">
                        <input type="text" name="buscar" class="form-control form-control-lg" placeholder="Buscar libros, artículos, autores..."/>
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Buscar</button>
                    </form>
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <a href="#destacados" class="btn btn-outline-light">Libros destacados</a>
                        <a href="#servicios" class="btn btn-outline-light">Nuestros servicios</a>
                        <a href="{{ route('libros.index') }}" class="btn btn-light">Explorar catálogo</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mensajes de éxito y error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <!-- Libros Destacados -->
    <section id="destacados" class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h2 class="fw-bold">Libros destacados</h2>
                <a href="{{ route('libros.index') }}" class="btn btn-outline-primary">Ver todos</a>
            </div>
            <div class="row g-4">
                @forelse($libros as $libro)
                    <div class="col-md-6 col-lg-3">
                        <div class="card book-card h-100">
                            <img
                                src="{{ $libro->portada ? asset($libro->portada) : asset('img/Libro1.jpg') }}"
                                class="card-img-top"
                                alt="{{ $libro->titulo }}"
                            >
                            <div class="card-body">
                                <h5 class="card-title">{{ $libro->titulo }}</h5>
                                <p class="card-text text-muted">{{ $libro->autor }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    @if($libro->estado === 'disponible' && $libro->disponibles > 0)
                                        <span class="badge bg-success">Disponible</span>
                                    @elseif($libro->estado === 'disponible' && $libro->disponibles == 0)
                                        <span class="badge bg-secondary">Sin stock</span>
                                    @elseif($libro->estado === 'prestado')
                                        <span class="badge bg-warning text-dark">Prestado</span>
                                    @else
                                        <span class="badge bg-danger">Dañado</span>
                                    @endif
                                    <button
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#solicitarPrestamoModal"
                                        data-libro-id="{{ $libro->id_libro }}"
                                        data-libro-titulo="{{ $libro->titulo }}"
                                        data-libro-disponibles="{{ $libro->disponibles }}"
                                        @if($libro->estado !== 'disponible' || $libro->disponibles < 1) disabled @endif
                                    >
                                        Solicitar préstamo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">No hay libros disponibles.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Servicios -->
    <section id="servicios" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Nuestros Servicios</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-md-5">
                    <div class="card h-100 border-0 text-center p-4">
                        <div class="feature-icon mb-3"><i class="bi bi-book"></i></div>
                        <h3>Préstamo de Libros</h3>
                        <p>Lleva hasta 3 libros hasta por 5 días</p>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card h-100 border-0 text-center p-4">
                        <div class="feature-icon mb-3"><i class="bi bi-laptop"></i></div>
                        <h3>Sala Digital</h3>
                        <p>Acceso a computadores, bases de datos académicas y eBooks, presencial</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Biblioteca Institucional</h5>
                    <p>Promoviendo el conocimiento y la cultura desde 1995.</p>

                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5>Enlaces</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('inicio') }}" class="text-white-50">Inicio</a></li>
                        <li><a href="{{ route('libros.index') }}" class="text-white-50">Catálogo</a></li>
                        <li><a href="{{ route('servicios') }}" class="text-white-50">Servicios</a></li>
                        <li><a href="{{ route('contacto') }}" class="text-white-50">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5>Contacto</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt me-2"></i> Tecsup Norte / Campus Trujillo el Golf</li>
                        <li><i class="bi bi-telephone me-2"></i> (01) 234-5678</li>
                        <li><i class="bi bi-envelope me-2"></i> biblioteca@tecsup.edu.pe</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Horario</h5>
                    <ul class="list-unstyled">
                        <li>Lunes a Viernes: 8am - 6pm</li>
                        <li>Sábados: 9am - 2pm</li>
                        <li>Domingos: Cerrado</li>
                    </ul>
                </div>
            </div>
                <p class="mb-0 small">&copy; 2025 Biblioteca Institucional. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Modal Solicitar Préstamo -->
    <div class="modal fade" id="solicitarPrestamoModal" tabindex="-1" aria-labelledby="solicitarPrestamoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="formPrestamo" method="POST" action="{{ route('prestamos.store') }}">
          @csrf
          <input type="hidden" name="id_libro" id="modal_id_libro">
          <input type="hidden" name="id_usuario" value="{{ session('usuario_id') }}">
          <input type="hidden" name="fecha_prestamo" value="{{ date('Y-m-d') }}">
          <input type="hidden" name="fecha_devolucion" id="fecha_devolucion">
          <input type="hidden" name="estado" value="pendiente">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="solicitarPrestamoLabel">Solicitar libro</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <div class="mb-2">
                <strong id="modal_libro_titulo"></strong>
                <div class="text-muted small">
                    Disponibles: <span id="modal_libro_disponibles"></span>
                </div>
              </div>
              <div class="mb-3">
                <label for="dias_prestamo" class="form-label">Duración del préstamo</label>
                <select class="form-select" name="dias_prestamo" id="dias_prestamo" required>
                  <option value="">Seleccione los días</option>
                  <option value="3">3 días</option>
                  <option value="4">4 días</option>
                  <option value="5">5 días</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="modalidad" class="form-label">Modalidad de entrega</label>
                <select class="form-select" name="modalidad" id="modalidad" required>
                  <option value="">Seleccione</option>
                  <option value="presencial">Presencial</option>
                </select>
              </div>
              <div class="alert alert-info small">
                Al presionar el botón "Solicitar préstamo" declaras que los datos consignados son reales y te pertenecen.
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Solicitar préstamo</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Manejo del cambio de días y cálculo de fecha de devolución
        document.getElementById('dias_prestamo').addEventListener('change', function() {
            const dias = parseInt(this.value);
            if (dias) {
                const fechaDevolucion = new Date();
                fechaDevolucion.setDate(fechaDevolucion.getDate() + dias);
                document.getElementById('fecha_devolucion').value = fechaDevolucion.toISOString().split('T')[0];
            }
        });

        // Validación del formulario antes de enviar
        document.getElementById('formPrestamo').addEventListener('submit', function(e) {
            const diasPrestamo = document.getElementById('dias_prestamo').value;
            const modalidad = document.getElementById('modalidad').value;

            if (!diasPrestamo || !modalidad) {
                e.preventDefault();
                alert('Por favor, complete todos los campos requeridos.');
                return false;
            }

            return true;
        });

        var prestamoModal = document.getElementById('solicitarPrestamoModal');
        prestamoModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var libroId = button.getAttribute('data-libro-id');
            var libroTitulo = button.getAttribute('data-libro-titulo');
            var libroDisponibles = button.getAttribute('data-libro-disponibles');
            document.getElementById('modal_id_libro').value = libroId;
            document.getElementById('modal_libro_titulo').textContent = libroTitulo;
            document.getElementById('modal_libro_disponibles').textContent = libroDisponibles;
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>






