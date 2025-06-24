{{-- Redirección si no h    .motivo-denegacion {
        background-color: #fff5f5;
        border-left: 3px solid #f56565;
        padding: 0.5rem;
        margin-top: 0.5rem;
        border-radius: 0.25rem;
    }

    /* Estilos para modo oscuro en historial - Solo cambiar fondo */
    @media (prefers-color-scheme: dark) {
        .modal-content {
            background-color: #2d2d2d !important;
            border: 1px solid #444 !important;
        }

        .modal-header.bg-primary {
            background-color: #0B5ED7 !important;
            color: #fff !important;
        }

        .modal-body {
            background-color: #2d2d2d !important;
        }

        .list-group-item {
            background-color: #333 !important;
            border-color: #444 !important;
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
    }

    /* Forzar estilos para modo oscuro cuando body tiene clase dark-theme - Solo cambiar fondo */
    body.dark-theme .modal-content,
    .dark-theme .modal-content {
        background-color: #2d2d2d !important;
        border: 1px solid #444 !important;
    }

    body.dark-theme .modal-header.bg-primary,
    .dark-theme .modal-header.bg-primary {
        background-color: #0B5ED7 !important;
        color: #fff !important;
    }

    body.dark-theme .modal-body,
    .dark-theme .modal-body {
        background-color: #2d2d2d !important;
    }

    body.dark-theme .list-group-item,
    .dark-theme .list-group-item {
        background-color: #333 !important;
        border-color: #444 !important;
    }

    body.dark-theme .btn-close,
    .dark-theme .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    body.dark-theme .modal-title,
    .dark-theme .modal-title {
        color: #fff !important;
    }ón --}}
@if(!session('usuario_id'))
    <script>window.location = "{{ route('login') }}";</script>
@endif
@extends('layouts.app')

@section('title', 'Servicios')


@push('styles')
<style>
    .estado-prestamo { font-weight: bold; padding: 0.2em 0.8em; border-radius: 0.5em; font-size: 1em; }
    .estado-en { background: #ffe066; color: #856404; }
    .estado-devuelto { background: #51cf66; color: #155724; }
    .estado-retrasado { background: #ffa8a8; color: #721c24; }
    .prestamo-card { border-left: 6px solid #dee2e6; background: #fff; margin-bottom: 1.5rem; }
    .prestamo-en { border-color: #ffe066; background: #fffbe6; }
    .prestamo-devuelto { border-color: #51cf66; background: #f3fff3; }
    .prestamo-retrasado { border-color: #ffa8a8; background: #fff0f0; }
    .prestamo-card .card-body { display: flex; gap: 1.5rem; align-items: center; }
    .prestamo-card img { width: 80px; height: 110px; object-fit: cover; border-radius: 8px; }
    .prestamo-info { flex: 1; }
    .prestamo-fechas { font-size: 0.98em; color: #555; }
    .prestamo-detalles-btn { min-width: 90px; }
    .motivo-denegacion {
        background-color: #fff5f5;
        border-left: 3px solid #f56565;
        padding: 0.5rem;
        margin-top: 0.5rem;
        border-radius: 0.25rem;
    }
    @media (max-width: 767px) {
        .prestamo-card .card-body { flex-direction: column; align-items: flex-start; }
        .prestamo-card img { margin-bottom: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Filtros -->
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Filtrar Historial</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('usuario.historial') }}">
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="estado[]" value="en_prestamo" id="en_prestamo"
                                        {{ in_array('en_prestamo', request('estado', ['en_prestamo','devuelto','retrasado'])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="en_prestamo">En préstamo</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="estado[]" value="devuelto" id="devuelto"
                                        {{ in_array('devuelto', request('estado', ['en_prestamo','devuelto','retrasado'])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="devuelto">Devuelto</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="estado[]" value="retrasado" id="retrasado"
                                        {{ in_array('retrasado', request('estado', ['en_prestamo','devuelto','retrasado'])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="retrasado">Retrasado</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="estado[]" value="pendiente" id="filtroPendiente"
                                        {{ in_array('pendiente', request('estado', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="filtroPendiente">Pendiente</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="estado[]" value="denegado" id="filtroDenegado"
                                        {{ in_array('denegado', request('estado', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="filtroDenegado">Denegado</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rango de fechas</label>
                            <select class="form-select" name="rango">
                                <option value="">Todo el historial</option>
                                <option value="30" {{ request('rango') == '30' ? 'selected' : '' }}>Últimos 30 días</option>
                                <option value="90" {{ request('rango') == '90' ? 'selected' : '' }}>Últimos 3 meses</option>
                                <option value="365" {{ request('rango') == '365' ? 'selected' : '' }}>Último año</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Buscar libro</label>
                            <input type="text" class="form-control" name="buscar" placeholder="Título del libro" value="{{ request('buscar') }}">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-2">Aplicar Filtros</button>
                        <a href="{{ route('usuario.historial') }}" class="btn btn-outline-secondary w-100">Restablecer</a>
                    </form>
                </div>
            </div>
        </div>
        <!-- Historial -->
        <div class="col-md-8 col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">Todos tus préstamos</h4>
                <span class="text-muted small">
                    Mostrando {{ $prestamos->firstItem() ?? 0 }}-{{ $prestamos->lastItem() ?? 0 }} de {{ $prestamos->total() }} préstamos
                </span>
            </div>
            @forelse($prestamos as $prestamo)
                @php
                    $estado = $prestamo->estado;
                    // Badge para el span de estado
                    switch ($estado) {
                        case 'pendiente':
                            $estadoTexto = 'Pendiente de aprobación';
                            $badge = 'estado-en';
                            $clase = 'prestamo-en';
                            break;
                        case 'activo':
                            $estadoTexto = 'En préstamo';
                            $badge = 'estado-en';
                            $clase = 'prestamo-en';
                            break;
                        case 'entregado':
                            $estadoTexto = 'Devuelto';
                            $badge = 'estado-devuelto';
                            $clase = 'prestamo-devuelto';
                            break;
                        case 'retraso':
                            $estadoTexto = 'Retrasado';
                            $badge = 'estado-retrasado';
                            $clase = 'prestamo-retrasado';
                            break;
                        case 'denegado':
                            $estadoTexto = 'Solicitud denegada';
                            $badge = 'estado-retrasado';
                            $clase = 'prestamo-retrasado';
                            break;
                        default:
                            $estadoTexto = ucfirst($estado);
                            $badge = 'estado-en';
                            $clase = 'prestamo-en';
                    }

                    // Cálculo de diferencia de tiempo solo si está en préstamo o retraso
                    $dias = $horas = $diffInSeconds = null;
                    if ($estado === 'activo' || $estado === 'retraso') {
                        $fechaLimite = \Carbon\Carbon::parse($prestamo->fecha_devolucion);
                        $ahora = \Carbon\Carbon::now();
                        $diffInSeconds = $fechaLimite->diffInSeconds($ahora, false);

                        $totalHoras = abs($fechaLimite->diffInHours($ahora, false));
                        $dias = intval(floor($totalHoras / 24));
                        $horas = intval($totalHoras % 24);
                    }
                @endphp
                <div class="card prestamo-card {{ $clase }}">
                    <div class="card-body">
                        <img src="{{ $prestamo->libro->portada ? asset($prestamo->libro->portada) : asset('img/Libro1.jpg') }}" alt="Portada">
                        <div class="prestamo-info">
                            <h5 class="mb-1">{{ $prestamo->libro->titulo }}</h5>
                            <div class="text-muted mb-1">{{ $prestamo->libro->autor }}</div>
                            <div class="prestamo-fechas mb-2">
                                <i class="bi bi-calendar2-plus me-1"></i> Préstamo: {{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d/m/Y') }}
                                <span class="mx-2">|</span>
                                <i class="bi bi-calendar2-check me-1"></i> Devolución: {{ \Carbon\Carbon::parse($prestamo->fecha_devolucion)->format('d/m/Y') }}
                                @if($estado === 'activo')
                                    <span class="mx-2">|</span>
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $diffInSeconds >= 0 ? "Quedan $dias días y $horas horas" : "$dias días y $horas horas de retraso" }}
                                @elseif($estado === 'entregado')
                                    <span class="mx-2">|</span>
                                    <i class="bi bi-check-circle me-1"></i>
                                    Devuelto a tiempo
                                @elseif($estado === 'retraso')
                                    <span class="mx-2">|</span>
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    {{ "$dias días y $horas horas de retraso" }}
                                @elseif($estado === 'pendiente')
                                    <span class="mx-2">|</span>
                                    <i class="bi bi-hourglass-split me-1"></i>
                                    Esperando aprobación
                                @elseif($estado === 'denegado')
                                    <span class="mx-2">|</span>
                                    <i class="bi bi-x-circle me-1"></i>
                                    Solicitud denegada
                                    @if($prestamo->observaciones)
                                        <br><small class="text-danger fst-italic">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Motivo: {{ $prestamo->observaciones }}
                                        </small>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-2">
                            <span class="estado-prestamo {{ $badge }}">
                                {{ $estadoTexto }}
                            </span>
                            <button class="btn btn-outline-primary prestamo-detalles-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#modalDetallePrestamo"
                                data-prestamo='{{ htmlentities(json_encode([
                                    "titulo" => $prestamo->libro->titulo,
                                    "autor" => $prestamo->libro->autor,
                                    "editorial" => $prestamo->libro->editorial,
                                    "anio" => $prestamo->libro->anio_publicacion,
                                    "fecha_prestamo" => $prestamo->fecha_prestamo,
                                    "fecha_devolucion" => $prestamo->fecha_devolucion,
                                    "fecha_entrega_real" => $prestamo->fecha_entrega_real,
                                    "estado" => $prestamo->estado,
                                    "observaciones" => $prestamo->observaciones
                                ]), ENT_QUOTES, 'UTF-8') }}'
                            >
                                Detalles
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">No tienes préstamos registrados.</div>
            @endforelse

            <div class="mt-4">
                {{ $prestamos->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- Modal para detalles --}}
<div class="modal fade" id="modalDetallePrestamo" tabindex="-1" aria-labelledby="modalDetallePrestamoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered animate__animated animate__fadeInDown">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalDetallePrestamoLabel">Detalle del Préstamo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><strong>Título:</strong> <span id="detalleTitulo"></span></li>
          <li class="list-group-item"><strong>Autor:</strong> <span id="detalleAutor"></span></li>
          <li class="list-group-item"><strong>Editorial:</strong> <span id="detalleEditorial"></span></li>
          <li class="list-group-item"><strong>Año:</strong> <span id="detalleAnio"></span></li>
          <li class="list-group-item"><strong>Fecha de préstamo:</strong> <span id="detalleFechaPrestamo"></span></li>
          <li class="list-group-item"><strong>Fecha de devolución:</strong> <span id="detalleFechaDevolucion"></span></li>
          <li class="list-group-item"><strong>Fecha entrega real:</strong> <span id="detalleFechaEntregaReal"></span></li>
          <li class="list-group-item"><strong>Estado:</strong> <span id="detalleEstado"></span></li>
          <li class="list-group-item" id="detalleObservacionesContainer" style="display: none;">
            <strong>Motivo de denegación:</strong>
            <span id="detalleObservaciones" class="text-danger"></span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('modalDetallePrestamo');
    modal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var prestamo = button.getAttribute('data-prestamo');
        if (prestamo) {
            // Reemplaza &quot; por "
            prestamo = JSON.parse(prestamo.replace(/&quot;/g, '"'));
            document.getElementById('detalleTitulo').textContent = prestamo.titulo ?? '-';
            document.getElementById('detalleAutor').textContent = prestamo.autor ?? '-';
            document.getElementById('detalleEditorial').textContent = prestamo.editorial ?? '-';
            document.getElementById('detalleAnio').textContent = prestamo.anio ?? '-';
            document.getElementById('detalleFechaPrestamo').textContent = prestamo.fecha_prestamo ?? '-';
            document.getElementById('detalleFechaDevolucion').textContent = prestamo.fecha_devolucion ?? '-';
            document.getElementById('detalleFechaEntregaReal').textContent = prestamo.fecha_entrega_real ?? '-';
            document.getElementById('detalleEstado').textContent = prestamo.estado.charAt(0).toUpperCase() + prestamo.estado.slice(1);

            // Mostrar observaciones solo si el estado es denegado y hay observaciones
            var observacionesContainer = document.getElementById('detalleObservacionesContainer');
            var observacionesSpan = document.getElementById('detalleObservaciones');
            if (prestamo.estado === 'denegado' && prestamo.observaciones) {
                observacionesSpan.textContent = prestamo.observaciones;
                observacionesContainer.style.display = 'block';
            } else {
                observacionesContainer.style.display = 'none';
            }
        }
    });
});
</script>
@endpush
@endsection

