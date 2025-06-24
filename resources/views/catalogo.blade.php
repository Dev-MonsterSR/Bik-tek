{{-- Redirección si no hay sesión --}}
@if(!session('usuario_id'))
    <script>window.location = "{{ route('login') }}";</script>
@endif
@extends('layouts.app')

@section('title', 'Catálogo')


@push('styles')
<style>
    .catalog-hero {
        position: relative;
        background: linear-gradient(120deg, rgba(11,28,43,0.92) 60%, rgba(0,0,0,0.7));
        color: #fff;
        padding: 6rem 0 4rem 0;
        margin-bottom: 2rem;
        border-bottom-left-radius: 2rem;
        border-bottom-right-radius: 2rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.15);
        min-height: 340px;
        display: flex;
        align-items: center;
    }
    .catalog-hero-content {
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
    }
    .catalog-hero .display-5 {
        font-weight: 900;
        letter-spacing: -1px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.18);
        font-size: 2.8rem;
        margin-bottom: 1.2rem;
    }
    .catalog-hero .lead {
        font-size: 1.35rem;
        margin-top: 0;
        color: #e0e0e0;
        text-shadow: 0 1px 4px rgba(0,0,0,0.12);
        margin-bottom: 0;
    }
    @media (max-width: 991px) {
        .catalog-hero { padding: 3rem 0 2rem 0; min-height: 220px; }
        .catalog-hero .display-5 { font-size: 2rem; }
        .catalog-hero-content { max-width: 100%; }
    }
    .book-card {
        transition: all 0.3s ease;
        height: 100%;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    .card-img-top {
        width: 100%;
        height: 260px;
        object-fit: cover;
        object-position: center;
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
    }
    .card-body {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .book-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.7em;
        border-radius: 0.5em;
    }
    .btn-outline-info {
        border-width: 1.5px;
        padding: 0.25rem 0.8rem;
    }
    .btn-primary, .btn-outline-primary {
        border-radius: 0.5em;
    }
    .d-grid .btn {
        margin-top: 0.5rem;
    }
    .filter-card {
        position: sticky;
        top: 20px;
    }
    @media (max-width: 991px) {
        .card-img-top { height: 180px; }
    }
</style>
@endpush
@section('content')
<!-- Hero Section del Catálogo -->
<section class="catalog-hero">
    <div class="container">
        <div class="catalog-hero-content">
            <h1 class="display-5 fw-bold">Explora nuestro catálogo completo</h1>
            <p class="lead">Variedad de libros, revistas y recursos importante a tu disposición</p>
            <p class="lead"> No esperes más y <span class="fw-bold text-primary">¡descubre tu próxima lectura favorita hoy mismo!</span></p>
        </div>
</section>

<!-- Filtros y Contenido Principal -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Columna de Filtros -->
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="card filter-card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Filtrar resultados</h5>
                    </div>
                    <div class="card-body">
                        <form id="filtrosForm" action="{{ route('libros.index') }}" method="GET">
                            <div class="mb-3">
                                <label for="buscar" class="form-label">Buscar</label>
                                <input type="text" name="buscar" id="buscar" class="form-control" value="{{ request('buscar') }}" placeholder="Título o autor">
                            </div>
                            <div class="mb-3">
                                <label for="categoria_id" class="form-label">Categoría</label>
                                <select name="categoria_id" id="categoria_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">Todas</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id_categoria }}" {{ request('categoria_id') == $categoria->id_categoria ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="ordenar" class="form-label">Ordenar por</label>
                                <select name="ordenar" id="ordenar" class="form-select" onchange="this.form.submit()">
                                    <option value="">Relevancia</option>
                                    <option value="titulo_asc" {{ request('ordenar') == 'titulo_asc' ? 'selected' : '' }}>Título (A-Z)</option>
                                    <option value="titulo_desc" {{ request('ordenar') == 'titulo_desc' ? 'selected' : '' }}>Título (Z-A)</option>
                                    <option value="autor_asc" {{ request('ordenar') == 'autor_asc' ? 'selected' : '' }}>Autor (A-Z)</option>
                                    <option value="autor_desc" {{ request('ordenar') == 'autor_desc' ? 'selected' : '' }}>Autor (Z-A)</option>
                                    <option value="anio_asc" {{ request('ordenar') == 'anio_asc' ? 'selected' : '' }}>Año (asc)</option>
                                    <option value="anio_desc" {{ request('ordenar') == 'anio_desc' ? 'selected' : '' }}>Año (desc)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Columna de Resultados -->
            <div class="col-lg-9">
                <!-- Mensajes de éxito o error -->
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

                <!-- Barra de herramientas -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="text-muted">
                            Mostrando {{ $libros->firstItem() ?? 0 }}-{{ $libros->lastItem() ?? 0 }} de {{ $libros->total() }} resultados
                        </span>
                    </div>
                </div>

                <!-- Lista de Libros -->
                <div class="row g-4">
                    @forelse($libros as $libro)
                        <div class="col-md-6 col-lg-4">
                            <div class="card book-card h-100">
                                <img
                                    src="{{ imagen_libro($libro->portada) }}"
                                    class="card-img-top"
                                    alt="{{ $libro->titulo }}"
                                >
                                <div class="card-body">
                                    <h5 class="card-title">{{ $libro->titulo }}</h5>
                                    <p class="card-text text-muted mb-2">{{ $libro->autor }}</p>
                                    <div class="book-actions mb-2">
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
                                            class="btn btn-sm btn-outline-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detalleLibroModal"
                                            data-libro='@json($libro)'
                                        >
                                            Ver detalles
                                        </button>
                                    </div>
                                    <div class="d-grid">
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

                <!-- Paginación -->
                <nav aria-label="Page navigation" class="mt-5">
                    {{ $libros->appends(request()->query())->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Modal Detalle de Libro -->
<div class="modal fade" id="detalleLibroModal" tabindex="-1" aria-labelledby="detalleLibroModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detalleLibroModalLabel">Detalles del libro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4 text-center mb-3 mb-md-0">
                <img id="detalleLibroPortada" src="" alt="Portada" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-8">
                <h4 id="detalleLibroTitulo"></h4>
                <p><strong>Autor:</strong> <span id="detalleLibroAutor"></span></p>
                <p><strong>Categoría:</strong> <span id="detalleLibroCategoria"></span></p>
                <p><strong>Año de publicación:</strong> <span id="detalleLibroAnio"></span></p>
                <p><strong>Editorial:</strong> <span id="detalleLibroEditorial"></span></p>
                <p><strong>Disponibles:</strong> <span id="detalleLibroDisponibles"></span></p>
                <p><strong>Estado:</strong> <span id="detalleLibroEstadoBadge"></span></p>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

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
@endsection


@push('scripts')
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

    // Modal Detalle de Libro
    var detalleModal = document.getElementById('detalleLibroModal');
    detalleModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var libro = button.getAttribute('data-libro');
        if (libro) {
            libro = JSON.parse(libro.replace(/&quot;/g,'"'));
            // Determinar la URL correcta de la imagen
            let imagenUrl = '/img/Libro1.jpg'; // imagen por defecto
            if (libro.portada) {
                if (libro.portada.startsWith('storage/') || libro.portada.startsWith('img/')) {
                    imagenUrl = '/' + libro.portada;
                } else {
                    imagenUrl = '/img/portadas/' + libro.portada;
                }
            }
            document.getElementById('detalleLibroPortada').src = imagenUrl;
            document.getElementById('detalleLibroTitulo').textContent = libro.titulo;
            document.getElementById('detalleLibroAutor').textContent = libro.autor;
            document.getElementById('detalleLibroCategoria').textContent = libro.categoria?.nombre ?? 'Sin categoría';
            document.getElementById('detalleLibroAnio').textContent = libro.anio_publicacion ?? '-';
            document.getElementById('detalleLibroEditorial').textContent = libro.editorial ?? '-';
            document.getElementById('detalleLibroDisponibles').textContent = libro.disponibles ?? '-';

            // Estado badge dinámico
            let estadoHtml = '';
            if (libro.estado === 'disponible' && libro.disponibles > 0) {
                estadoHtml = '<span class="badge bg-success">Disponible</span>';
            } else if (libro.estado === 'disponible' && libro.disponibles == 0) {
                estadoHtml = '<span class="badge bg-danger">Sin stock</span>';
            } else if (libro.estado === 'prestado') {
                estadoHtml = '<span class="badge bg-warning text-dark">Prestado</span>';
            } else {
                estadoHtml = '<span class="badge bg-secondary">Dañado</span>';
            }
            document.getElementById('detalleLibroEstadoBadge').innerHTML = estadoHtml;
        }
    });

    // Modal Solicitar Préstamo
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
@endpush
