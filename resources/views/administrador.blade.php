@if(!session('admin_id'))
    <script>window.location = "{{ route('login') }}";</script>
    @php exit; @endphp
@endif
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Administrador - Biblioteca Tecsup Trujillo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        /* Sidebar mejorado */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #0B1C2B 0%, #1a2332 100%);
            color: #fff;
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: #e9ecef;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 2px 8px;
            padding: 12px 16px;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: linear-gradient(90deg, #0B5ED7 0%, #198754 100%);
            color: #fff;
            transform: translateX(8px);
            box-shadow: 0 4px 15px rgba(11, 94, 215, 0.3);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Cards de métricas profesionales */
        .metric-card {
            border-radius: 16px;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            border: 0;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .metric-card .card-body {
            padding: 2rem;
        }

        .metric-icon {
            position: absolute;
            top: -15px;
            right: -15px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.15;
            font-size: 3rem;
        }

        /* Gráficos compactos */
        .chart-card {
            border-radius: 12px;
            border: 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: 0 5px 25px rgba(0,0,0,0.12);
        }

        /* Modo oscuro mejorado */
        body.dark-mode {
            background: #1a1d23 !important;
            color: #f1f1f1 !important;
        }

        body.dark-mode .metric-card,
        body.dark-mode .chart-card,
        body.dark-mode .card {
            background: #2d3238 !important;
            color: #f1f1f1 !important;
            border-color: #444 !important;
        }

        body.dark-mode .bg-light {
            background-color: #3a4148 !important;
            color: #fff !important;
        }

        body.dark-mode .bg-white {
            background-color: #2d3238 !important;
            color: #f1f1f1 !important;
        }

        body.dark-mode .text-muted {
            color: #adb5bd !important;
        }

        body.dark-mode .card-header {
            background-color: #3a4148 !important;
            color: #f1f1f1 !important;
            border-bottom-color: #444 !important;
        }

        body.dark-mode .table {
            color: #f1f1f1 !important;
        }

        body.dark-mode .table-light {
            background-color: #3a4148 !important;
            color: #f1f1f1 !important;
        }

        body.dark-mode .border-bottom {
            border-bottom-color: #444 !important;
        }

        body.dark-mode .progress {
            background-color: #3a4148 !important;
        }

        body.dark-mode .alert-warning {
            background-color: #664d03 !important;
            color: #fff3cd !important;
            border-color: #b08d47 !important;
        }

        body.dark-mode .alert-danger {
            background-color: #721c24 !important;
            color: #f8d7da !important;
            border-color: #a52834 !important;
        }

        body.dark-mode .alert-info {
            background-color: #055160 !important;
            color: #b6effb !important;
            border-color: #4d8a99 !important;
        }

        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background-color: #3a4148 !important;
            color: #f1f1f1 !important;
            border-color: #555 !important;
        }

        body.dark-mode .form-control:focus,
        body.dark-mode .form-select:focus {
            background-color: #3a4148 !important;
            color: #f1f1f1 !important;
            border-color: #0B5ED7 !important;
            box-shadow: 0 0 0 0.25rem rgba(11, 94, 215, 0.25) !important;
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .spin { animation: spin 1s linear infinite; }

        /* Estilos para modal de edición */
        .edit-form-row {
            display: flex;
            gap: 24px;
        }
        .edit-form-col {
            flex: 1 1 0;
        }
        .img-preview {
            border: 1px solid #e3e3e3;
            background: #fff;
            padding: 6px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: box-shadow 0.3s;
        }
        .img-preview img {
            transition: transform 0.3s;
        }
        .img-preview img:hover {
            transform: scale(1.08) rotate(-2deg);
        }

        /* Modo oscuro para modal de edición */
        body.dark-mode .img-preview {
            border-color: #555;
            background: #3a4148;
        }
        .edit-form-row {
            display: flex;
            gap: 16px;
        }
        .edit-form-col {
            flex: 1 1 0;
        }

        .img-preview {
            border: 1px solid #e3e3e3;
            background: #fff;
            padding: 6px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: box-shadow 0.3s;
        }

        .img-preview img {
            transition: transform 0.3s;
        }

        .img-preview img:hover {
            transform: scale(1.08) rotate(-2deg);
        }

        /* Modo oscuro para modal de edición */
        body.dark-mode .img-preview {
            border-color: #555;
            background: #3a4148;
        }

        body.dark-mode .modal-content {
            background-color: #2d3238 !important;
            color: #f1f1f1 !important;
        }

        body.dark-mode .modal-header {
            background: linear-gradient(90deg, #0B5ED7 60%, #0B1C2B 100%) !important;
            color: #fff !important;
            border-bottom-color: #444 !important;
        }

        body.dark-mode .modal-body {
            background-color: #2d3238 !important;
            color: #f1f1f1 !important;
        }

        body.dark-mode .modal-footer {
            background-color: #2d3238 !important;
            border-top-color: #444 !important;
        }

        /* Estilos para Toasts */
        .toast {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        body.dark-mode .toast {
            background-color: #374151 !important;
            border-color: #4b5563;
            color: #f9fafb !important;
        }

        body.dark-mode .toast .toast-body {
            color: #f9fafb !important;
        }

        body.dark-mode .toast.text-success {
            background-color: #065f46 !important;
            color: #d1fae5 !important;
        }

        body.dark-mode .toast.text-success .toast-body {
            color: #d1fae5 !important;
        }

        body.dark-mode .toast.text-danger {
            background-color: #7f1d1d !important;
            color: #fecaca !important;
        }

        body.dark-mode .toast.text-danger .toast-body {
            color: #fecaca !important;
        }

        body.dark-mode .toast.text-info {
            background-color: #0c4a6e !important;
            color: #bae6fd !important;
        }

        body.dark-mode .toast.text-info .toast-body {
            color: #bae6fd !important;
        }

        body.dark-mode .btn-close-dark {
            filter: brightness(0) saturate(100%) invert(89%) sepia(6%) saturate(106%) hue-rotate(204deg) brightness(106%) contrast(106%);
        }

        /* Estilos para modales de detalles */
        .info-card {
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        body.dark-mode .modal-content {
            background-color: #2d3238 !important;
            color: #f1f1f1 !important;
            border-color: #444 !important;
        }

        body.dark-mode .modal-header {
            background-color: #17a2b8 !important;
            border-bottom-color: #444 !important;
        }

        body.dark-mode .modal-footer {
            background-color: #3a4148 !important;
            border-top-color: #444 !important;
        }

        body.dark-mode .info-card {
            background-color: #3a4148 !important;
            color: #f1f1f1 !important;
            border-color: #555 !important;
        }

        body.dark-mode .badge {
            background-color: #198754 !important;
        }

        /* Responsivo */
        @media (max-width: 768px) {
            .metric-icon { display: none; }
            .sidebar { min-height: auto; }
            .edit-form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar py-4">
                <div class="text-center mb-4">
                    <img src="{{ asset('img/LocalB/logo.jpg') }}" alt="Logo" width="50" height="50" class="mb-2 rounded-circle">
                    <h5 class="fw-bold text-white">Administrador</h5>
                    <small class="text-light opacity-75">Biblioteca Tecsup</small>
                </div>

                <ul class="nav flex-column mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-dashboard" href="#" onclick="showTab('dashboard')">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tab-inventario" href="#" onclick="showTab('inventario')">
                            <i class="bi bi-book"></i> Inventario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-cuentas" href="#" onclick="showTab('cuentas')">
                            <i class="bi bi-people"></i> Cuentas
                        </a>
                    </li>
                </ul>

                <div class="mt-auto">
                    <a href="{{ route('inicio') }}" class="nav-link text-light opacity-75">
                        <i class="bi bi-house"></i> Sitio Principal
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent text-light opacity-75 w-100 text-start">
                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </nav>

            <!-- Contenido Principal -->
            <main class="col-md-10 ms-sm-auto px-4 py-3">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Dashboard Ejecutivo</h1>
                        <p class="text-muted mb-0">Análisis completo del sistema bibliotecario</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalExportar">
                            <i class="bi bi-download me-1"></i>Exportar
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="actualizarDatos()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Actualizar
                        </button>
                        <button id="toggleTheme" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                    </div>
                </div>

                <!-- Mensajes Flash -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Panel Dashboard -->
                <div id="panel-dashboard" class="fade-in">
                    <!-- Métricas Principales -->
                    <div class="row mb-4 g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="metric-card h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <div class="card-body text-white position-relative">
                                    <h3 class="fw-bold mb-1" id="prestamosTotales">{{ \App\Models\Prestamo::count() }}</h3>
                                    <p class="mb-1 opacity-90">Préstamos Totales</p>
                                    <small class="opacity-75">
                                        <i class="bi bi-arrow-up me-1"></i>+12% este mes
                                    </small>
                                    <div class="metric-icon">
                                        <i class="bi bi-book-half"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="metric-card h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <div class="card-body text-white position-relative">
                                    <h3 class="fw-bold mb-1" id="usuariosActivos">{{ \App\Models\Usuario::count() }}</h3>
                                    <p class="mb-1 opacity-90">Usuarios Registrados</p>
                                    <small class="opacity-75">
                                        <i class="bi bi-arrow-up me-1"></i>+8% este mes
                                    </small>
                                    <div class="metric-icon">
                                        <i class="bi bi-people"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="metric-card h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <div class="card-body text-white position-relative">
                                    <h3 class="fw-bold mb-1" id="devolucionesTotales">{{ \App\Models\Devolucion::count() }}</h3>
                                    <p class="mb-1 opacity-90">Devoluciones</p>
                                    <small class="opacity-75">
                                        <i class="bi bi-check-circle me-1"></i>96% completadas
                                    </small>
                                    <div class="metric-icon">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="metric-card h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                <div class="card-body text-white position-relative">
                                    <h3 class="fw-bold mb-1" id="sancionesActivas">{{ \App\Models\Sancion::where('estado', 'activa')->count() }}</h3>
                                    <p class="mb-1 opacity-90">Sanciones Activas</p>
                                    <small class="opacity-75">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Requieren atención
                                    </small>
                                    <div class="metric-icon">
                                        <i class="bi bi-shield-exclamation"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos -->
                    <div class="row mb-4">
                        <div class="col-lg-8">
                            <div class="chart-card">
                                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-1">Actividad del Sistema</h6>
                                        <small class="text-muted">Últimos 30 días</small>
                                    </div>
                                    <select class="form-select form-select-sm" id="tipoGrafico" onchange="actualizarGrafico()" style="width: 140px;">
                                        <option value="prestamos">Préstamos</option>
                                        <option value="devoluciones">Devoluciones</option>
                                        <option value="usuarios">Usuarios</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <canvas id="graficoPrincipal" height="100"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="chart-card h-100">
                                <div class="card-header bg-white border-0">
                                    <h6 class="fw-bold mb-1">Estado de Préstamos</h6>
                                    <small class="text-muted">Distribución actual</small>
                                </div>
                                <div class="card-body">
                                    <canvas id="graficoEstados" height="120"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas Compactas -->
                    <div class="row mb-4">
                        <div class="col-lg-4">
                            <div class="chart-card h-100">
                                <div class="card-header bg-white border-0">
                                    <h6 class="fw-bold mb-1">Indicadores de desempeño del sistema</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small>Libros Disponibles</small>
                                            <small class="fw-bold" id="kpiLibrosDisponibles">
                                                @php
                                                    $totalLibros = \App\Models\Libro::sum('cantidad');
                                                    $disponibles = \App\Models\Libro::sum('disponibles');
                                                    $porcentaje = $totalLibros > 0 ? round(($disponibles / $totalLibros) * 100) : 0;
                                                @endphp
                                                {{ $porcentaje }}%
                                            </small>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: {{ $porcentaje }}%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small>Préstamos Activos</small>
                                            <small class="fw-bold" id="kpiPrestamosActivos">
                                                @php
                                                    $prestamosActivos = \App\Models\Prestamo::where('estado', 'activo')->count();
                                                    $totalPrestamos = \App\Models\Prestamo::count();
                                                    $porcentajeActivos = $totalPrestamos > 0 ? round(($prestamosActivos / $totalPrestamos) * 100) : 0;
                                                @endphp
                                                {{ $porcentajeActivos }}%
                                            </small>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-info" style="width: {{ $porcentajeActivos }}%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small>Usuarios Activos</small>
                                            <small class="fw-bold" id="kpiUsuariosActivos">
                                                @php
                                                    $usuariosConPrestamos = \App\Models\Usuario::whereHas('prestamos')->count();
                                                    $totalUsuarios = \App\Models\Usuario::count();
                                                    $porcentajeUsuarios = $totalUsuarios > 0 ? round(($usuariosConPrestamos / $totalUsuarios) * 100) : 0;
                                                @endphp
                                                {{ $porcentajeUsuarios }}%
                                            </small>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-warning" style="width: {{ $porcentajeUsuarios }}%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small>Tasa de Devolución</small>
                                            <small class="fw-bold" id="kpiTasaDevolucion">
                                                @php
                                                    $devoluciones = \App\Models\Devolucion::count();
                                                    $prestamosCompletados = \App\Models\Prestamo::where('estado', 'completado')->count();
                                                    $tasaDevolucion = $prestamosCompletados > 0 ? round(($devoluciones / $prestamosCompletados) * 100) : 0;
                                                @endphp
                                                {{ $tasaDevolucion }}%
                                            </small>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary" style="width: {{ $tasaDevolucion }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="chart-card h-100">
                                <div class="card-header bg-white border-0">
                                    <h6 class="fw-bold mb-1">Top Libros</h6>
                                    <small class="text-muted">Más solicitados</small>
                                </div>
                                <div class="card-body">
                                    <div id="topLibros">
                                        <!-- Contenido dinámico -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="chart-card h-100">
                                <div class="card-header bg-white border-0">
                                    <h6 class="fw-bold mb-1">Alertas</h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning py-2 mb-2">
                                        <i class="bi bi-clock text-warning me-2"></i>
                                        <span id="prestamosVencidos">3</span> préstamos vencidos
                                    </div>
                                    <div class="alert alert-danger py-2 mb-2">
                                        <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                                        <span id="sancionesVencidas">1</span> sanción por revisar
                                    </div>
                                    <div class="alert alert-info py-2 mb-0">
                                        <i class="bi bi-info-circle text-info me-2"></i>
                                        Sistema funcionando correctamente
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Generador de Reportes -->
                    <div class="chart-card mb-4">
                        <div class="card-header bg-white border-0">
                            <h6 class="fw-bold mb-1">Generador de Reportes</h6>
                        </div>
                        <div class="card-body">
                            <form class="row g-3" onsubmit="event.preventDefault(); generarReporte();">
                                <div class="col-md-3">
                                    <select class="form-select" id="tipoReporte">
                                        <option value="prestamos">Préstamos</option>
                                        <option value="devoluciones">Devoluciones</option>
                                        <option value="usuarios">Usuarios</option>
                                        <option value="sanciones">Sanciones</option>
                                        <option value="libros">Libros más solicitados</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" class="form-control" id="fechaInicio">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" class="form-control" id="fechaFin">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-table me-1"></i>Generar Reporte
                                    </button>
                                </div>
                            </form>

                            <!-- Tabla de resultados -->
                            <div id="reporteResultado" class="mt-4" style="display:none;">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="tablaReporte">
                                        <thead class="table-light" id="theadReporte"></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="reporteVacio" class="alert alert-info mt-3" style="display:none;">
                                <i class="bi bi-info-circle me-2"></i>No hay datos para el período seleccionado.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Sanciones -->
                <div id="panel-sanciones" style="display:none;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="fw-bold mb-1">Gestión de Sanciones</h3>
                            <p class="text-muted mb-0">Administra las sanciones aplicadas a los usuarios</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="exportarSanciones()">
                                <i class="bi bi-download me-1"></i>Exportar
                            </button>
                            <button class="btn btn-primary btn-sm" onclick="actualizarSanciones()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Actualizar
                            </button>
                        </div>
                    </div>

                    <!-- Métricas de Sanciones -->
                    <div class="row mb-4 g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="metric-card h-100" style="background: linear-gradient(135deg, #dc3545 0%, #ffc107 100%);">
                                <div class="card-body text-white position-relative">
                                    <h3 class="fw-bold mb-1" id="sancionesActivasTotal">0</h3>
                                    <p class="mb-1 opacity-90">Sanciones Activas</p>
                                    <small class="opacity-75">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Requieren atención
                                    </small>
                                    <div class="metric-icon">
                                        <i class="bi bi-shield-exclamation"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="metric-card h-100" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                <div class="card-body text-white position-relative">
                                    <h3 class="fw-bold mb-1" id="sancionesCumplidasMes">0</h3>
                                    <p class="mb-1 opacity-90">Cumplidas Este Mes</p>
                                    <small class="opacity-75">
                                        <i class="bi bi-check-circle me-1"></i>Finalizadas
                                    </small>
                                    <div class="metric-icon">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="metric-card h-100" style="background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);">
                                <div class="card-body text-white position-relative">
                                    <h3 class="fw-bold mb-1" id="usuariosSancionadosTotal">0</h3>
                                    <p class="mb-1 opacity-90">Usuarios Sancionados</p>
                                    <small class="opacity-75">
                                        <i class="bi bi-people me-1"></i>Únicos
                                    </small>
                                    <div class="metric-icon">
                                        <i class="bi bi-people"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="metric-card h-100" style="background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);">
                                <div class="card-body text-white position-relative">
                                    <h3 class="fw-bold mb-1" id="promedioDiasSancion">0</h3>
                                    <p class="mb-1 opacity-90">Promedio Días</p>
                                    <small class="opacity-75">
                                        <i class="bi bi-calendar me-1"></i>Por sanción
                                    </small>
                                    <div class="metric-icon">
                                        <i class="bi bi-calendar3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros y tabla de sanciones -->
                    <div class="chart-card mb-4">
                        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-1">Lista de Sanciones</h6>
                            <button class="btn btn-warning btn-sm" onclick="abrirModalNuevaSancion()">
                                <i class="bi bi-plus-circle me-1"></i>Nueva Sanción
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Filtros -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="buscarUsuarioSancion" placeholder="Buscar usuario...">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="filtroEstadoSancion">
                                        <option value="">Todos los estados</option>
                                        <option value="activa">Activas</option>
                                        <option value="cumplida">Cumplidas</option>
                                        <option value="levantada">Levantadas</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="filtroTipoSancion">
                                        <option value="">Todos los tipos</option>
                                        <option value="retraso">Retraso</option>
                                        <option value="daño">Daño</option>
                                        <option value="perdida">Pérdida</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary w-100" onclick="filtrarSanciones()">
                                        <i class="bi bi-funnel me-1"></i>Filtrar
                                    </button>
                                </div>
                            </div>

                            <!-- Tabla -->
                            <div class="table-responsive">
                                <table class="table table-hover" id="tablaSanciones">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Tipo</th>
                                            <th>Días</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Estado</th>
                                            <th>Observaciones</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpoTablaSanciones">
                                        <!-- Contenido dinámico -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Inventario -->
                <div id="panel-inventario" style="display:none;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold">Inventario de Libros</h2>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLibro">
                            <i class="bi bi-plus-circle me-2"></i>Agregar libro
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tablaLibros">
                            <thead>
                        <tr>
                            <th>Código</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>Disponibles</th>
                            <th>Estado</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($libros as $libro)
                            <tr>
                                <td>{{ $libro->codigo }}</td>
                                <td>{{ $libro->titulo }}</td>
                                <td>{{ $libro->autor }}</td>
                                <td>{{ $libro->categoria ? $libro->categoria->nombre : '' }}</td>
                                <td>{{ $libro->cantidad }}</td>
                                <td>{{ $libro->disponibles }}</td>
                                <td>
                                    <span class="badge {{ $libro->estado == 'disponible' ? 'bg-success' : ($libro->estado == 'prestado' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($libro->estado) }}
                                    </span>
                                </td>
                                <td>
                                    @if($libro->portada)
                                        <img src="{{ imagen_libro($libro->portada) }}" alt="Portada" width="40" height="60">
                                    @else
                                        <span class="text-muted">Sin portada</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="abrirModalEditarLibro({{ $libro->id_libro }})" title="Editar libro">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    @php
                                        $prestamosActivos = $libro->prestamos()->whereIn('estado', ['pendiente', 'activo', 'retraso'])->count();
                                    @endphp
                                    @if($prestamosActivos > 0)
                                        <button class="btn btn-sm btn-secondary" disabled title="No se puede eliminar: tiene {{ $prestamosActivos }} préstamo(s) activo(s)">
                                            <i class="bi bi-lock"></i>
                                        </button>
                                    @else
                                        <form action="{{ route('libros.destroy', $libro->id_libro) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar el libro &quot;{{ addslashes($libro->titulo) }}&quot;?')" title="Eliminar libro">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                        </table>
                    </div>
                </div>

                <!-- Panel Cuentas -->
                <div id="panel-cuentas" style="display:none;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold">Gestión de Cuentas</h2>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                            <input type="text" id="searchTrabajadores" class="form-control" placeholder="Buscar bibliotecarios...">
                                            <button class="btn btn-outline-secondary" type="button" onclick="search('trabajadores')">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text" id="searchUsuarios" class="form-control" placeholder="Buscar usuarios...">
                                            <button class="btn btn-outline-secondary" type="button" onclick="search('usuarios')">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTrabajador">
                            <i class="bi bi-person-plus me-2"></i>Agregar Bibliotecario
                        </button>
                    </div>
                    <h4>Trabajadores</h4>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Nombre</th>
                                    <th>Rol</th>
                                    <th>Email</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trabajadores as $trabajador)
                                    <tr>
                                        <td>{{ $trabajador->usuario }}</td>
                                        <td>{{ $trabajador->nombre }}</td>
                                        <td>Bibliotecario</td>
                                        <td>{{ $trabajador->email }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info me-1" onclick="verDetallesTrabajador({{ $trabajador->id_trabajador }})">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <form action="{{ route('trabajadores.destroy', $trabajador) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta cuenta?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <h4>Usuarios</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Email</th>
                                    <th>Fecha de Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuarios as $usuario)
                                    <tr>
                                        <td>{{ $usuario->nombre }}</td>
                                        <td>{{ $usuario->apellido }}</td>
                                        <td>{{ $usuario->email }}</td>
                                        <td>{{ $usuario->fecha_registro }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info me-1" onclick="verDetallesUsuario({{ $usuario->id_usuario }})">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta cuenta?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal para Nueva Sanción -->
    <div class="modal fade" id="modalNuevaSancion" tabindex="-1" aria-labelledby="modalNuevaSancionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalNuevaSancionLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Aplicar Nueva Sanción
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formNuevaSancion">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="usuario_sancion" class="form-label">Usuario</label>
                            <select class="form-select" id="usuario_sancion" name="id_usuario" required>
                                <option value="">Seleccionar usuario...</option>
                                @foreach(\App\Models\Usuario::all() as $usuario)
                                    <option value="{{ $usuario->id_usuario }}">
                                        {{ $usuario->nombre }} {{ $usuario->apellido }} ({{ $usuario->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_sancion_nueva" class="form-label">Tipo de sanción</label>
                            <select class="form-select" id="tipo_sancion_nueva" name="tipo" required>
                                <option value="">Seleccionar tipo...</option>
                                <option value="retraso">Retraso en devolución</option>
                                <option value="daño">Daño al libro</option>
                                <option value="perdida">Pérdida del libro</option>
                                <option value="otro">Otro motivo</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="dias_sancion" class="form-label">Días de bloqueo</label>
                            <input type="number" class="form-control" id="dias_sancion" name="dias_bloqueo"
                                   min="1" max="365" required>
                            <div class="form-text">Número de días que el usuario estará bloqueado</div>
                        </div>

                        <div class="mb-3">
                            <label for="observaciones_sancion_nueva" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones_sancion_nueva" name="observaciones"
                                      rows="3" required placeholder="Describa el motivo de la sanción..."></textarea>
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Importante:</strong> Esta acción bloqueará al usuario inmediatamente y no podrá solicitar préstamos hasta que se complete la sanción.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-exclamation-triangle me-1"></i>Aplicar Sanción
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Agregar/Editar Libro -->
    <div class="modal fade" id="modalLibro" tabindex="-1" aria-labelledby="modalLibroLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('libros.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="modalLibroLabel">Agregar libro</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-white text-dark">
                    <div id="libroFormErrors" class="alert alert-danger d-none"></div>
                    <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="codigoLibro" class="form-label text-dark">Código</label>
                        <input type="text" class="form-control" id="codigoLibro" name="codigo" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tituloLibro" class="form-label text-dark">Título</label>
                        <input type="text" class="form-control" id="tituloLibro" name="titulo" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="autorLibro" class="form-label text-dark">Autor</label>
                        <input type="text" class="form-control" id="autorLibro" name="autor">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="editorialLibro" class="form-label text-dark">Editorial</label>
                        <input type="text" class="form-control" id="editorialLibro" name="editorial">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="anioLibro" class="form-label text-dark">Año de publicación</label>
                        <input type="number" class="form-control" id="anioLibro" name="anio_publicacion">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="categoriaLibro" class="form-label text-dark">Categoría</label>
                        <select class="form-select" id="categoriaLibro" name="categoria_id">
                            <option value="">Selecciona una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cantidadLibro" class="form-label text-dark">Cantidad</label>
                        <input type="number" class="form-control" id="cantidadLibro" name="cantidad" min="1" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="disponiblesLibro" class="form-label text-dark">Disponibles</label>
                        <input type="number" class="form-control" id="disponiblesLibro" name="disponibles" min="0" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="estadoLibro" class="form-label text-dark">Estado</label>
                        <select class="form-select" id="estadoLibro" name="estado" required>
                            <option value="disponible">Disponible</option>
                            <option value="prestado">Prestado</option>
                            <option value="dañado">Dañado</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="portadaLibro" class="form-label text-dark">Portada</label>
                        <input type="file" class="form-control" id="portadaLibro" name="portada" accept="image/*">
                    </div>
                </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Agregar/Editar Cuenta -->
    <div class="modal fade" id="modalTrabajador" tabindex="-1" aria-labelledby="modalTrabajadorLabel">
        <div class="modal-dialog">
            <form id="formTrabajador" method="POST" action="{{ route('trabajadores.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title text-white">Agregar Bibliotecario</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body bg-white text-dark">
                        <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label text-dark">Usuario*</label>
            <input type="text" name="usuario" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label text-dark">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label text-dark">Nombre*</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="apellido" class="form-label text-dark">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="dni" class="form-label text-dark">DNI</label>
            <input type="text" class="form-control" id="dni" name="dni" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="telefono" class="form-label text-dark">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono">
        </div>
    </div>
    <div class="mb-3">
        <label for="direccion" class="form-label text-dark">Dirección</label>
        <input type="text" class="form-control" id="direccion" name="direccion">
    </div>
    <div class="mb-3">
        <label class="form-label text-dark">Contraseña*</label>
        <input type="password" name="password" class="form-control" required>
    </div>
                    </div>
                    <div class="modal-footer bg-white">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sistema de Toasts para notificaciones no bloqueantes
        function showToast(message, type = 'success', duration = 4000) {
            const toastContainer = document.querySelector('.toast-container');
            const toastId = 'toast-' + Date.now();

            // Iconos según el tipo
            const icons = {
                success: 'bi-check-circle-fill',
                error: 'bi-exclamation-triangle-fill',
                info: 'bi-info-circle-fill'
            };

            // Colores según el tipo
            const colors = {
                success: 'text-success bg-light',
                error: 'text-danger bg-light',
                info: 'text-info bg-light'
            };

            // Determinar si estamos en modo oscuro
            const isDarkMode = document.body.classList.contains('dark-mode');
            const closeButtonClass = isDarkMode ? 'btn-close btn-close-white' : 'btn-close btn-close-dark';

            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center ${colors[type]} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="bi ${icons[type]} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="${closeButtonClass} me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHtml);

            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                delay: duration,
                autohide: true
            });

            // Mostrar el toast
            toast.show();

            // Remover del DOM después de que se oculte
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }

        // Funciones auxiliares para tipos específicos
        function showSuccessToast(message, duration = 4000) {
            showToast(message, 'success', duration);
        }

        function showErrorToast(message, duration = 6000) {
            showToast(message, 'error', duration);
        }



        function showInfoToast(message, duration = 4000) {
            showToast(message, 'info', duration);
        }

        // Función global para restaurar el scroll y limpiar modales
        function restaurarScrollYLimpiarModales() {
            // Remover todos los backdrops que puedan quedar
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                backdrop.remove();
            });

            // Restaurar todas las propiedades del body
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';

            // Forzar overflow visible si está oculto
            if (document.body.style.overflow === 'hidden') {
                document.body.style.overflow = 'auto';
            }

            // Limpiar clases de Bootstrap que puedan estar bloqueando
            const html = document.documentElement;
            html.style.removeProperty('overflow');
            html.style.removeProperty('padding-right');

            // Forzar repaint
            document.body.offsetHeight;

            console.log('Scroll restaurado - Body overflow:', document.body.style.overflow);
        }

        let graficoPrincipal = null;
        let graficoEstados = null;

        // Navegación entre paneles
        function showTab(tab) {
            // Asegurar que el scroll esté funcionando al cambiar pestañas
            restaurarScrollYLimpiarModales();

            // Ocultar todos los paneles
            document.querySelectorAll('[id^="panel-"]').forEach(panel => {
                panel.style.display = 'none';
            });

            // Mostrar panel seleccionado
            document.getElementById('panel-' + tab).style.display = 'block';

            // Actualizar navegación
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            document.getElementById('tab-' + tab).classList.add('active');

            // Cargar datos específicos
            if (tab === 'dashboard') {
                cargarDatos();
            } else if (tab === 'sanciones') {
                cargarMetricasSanciones();
                cargarTablaSanciones();
            }
        }

        // Cargar datos del dashboard
        function cargarDatos() {
            cargarMetricas();
            actualizarGrafico();
            cargarGraficoEstados();
            cargarTopLibros();
            cargarKPIs();
        }

        function cargarKPIs() {
            fetch("{{ route('admin.reportes.grafico') }}?tipo=kpis")
                .then(res => res.json())
                .then(data => {
                    // Actualizar KPIs dinámicamente si los datos están disponibles
                    if (data.libros_disponibles_pct !== undefined) {
                        document.getElementById('kpiLibrosDisponibles').textContent = data.libros_disponibles_pct + '%';
                        document.querySelector('#kpiLibrosDisponibles').parentElement.parentElement.querySelector('.progress-bar').style.width = data.libros_disponibles_pct + '%';
                    }
                    if (data.prestamos_activos_pct !== undefined) {
                        document.getElementById('kpiPrestamosActivos').textContent = data.prestamos_activos_pct + '%';
                        document.querySelector('#kpiPrestamosActivos').parentElement.parentElement.querySelector('.progress-bar').style.width = data.prestamos_activos_pct + '%';
                    }
                    if (data.usuarios_activos_pct !== undefined) {
                        document.getElementById('kpiUsuariosActivos').textContent = data.usuarios_activos_pct + '%';
                        document.querySelector('#kpiUsuariosActivos').parentElement.parentElement.querySelector('.progress-bar').style.width = data.usuarios_activos_pct + '%';
                    }
                    if (data.tasa_devolucion_pct !== undefined) {
                        document.getElementById('kpiTasaDevolucion').textContent = data.tasa_devolucion_pct + '%';
                        document.querySelector('#kpiTasaDevolucion').parentElement.parentElement.querySelector('.progress-bar').style.width = data.tasa_devolucion_pct + '%';
                    }
                })
                .catch(error => console.error('Error cargando KPIs:', error));
        }

        function cargarMetricas() {
            fetch("{{ route('admin.reportes.grafico') }}?tipo=metricas")
                .then(res => res.json())
                .then(data => {
                    document.getElementById('prestamosTotales').textContent = data.prestamos || 0;
                    document.getElementById('usuariosActivos').textContent = data.usuarios_totales || 0;
                    document.getElementById('devolucionesTotales').textContent = data.devoluciones || 0;
                    document.getElementById('sancionesActivas').textContent = data.sanciones_activas || 0;
                    document.getElementById('prestamosVencidos').textContent = data.prestamos_vencidos || 0;
                    document.getElementById('sancionesVencidas').textContent = data.sanciones_vencidas || 0;
                })
                .catch(error => console.error('Error:', error));
        }

        function actualizarGrafico() {
            const tipo = document.getElementById('tipoGrafico').value;

            fetch(`{{ route('admin.reportes.grafico') }}?tipo=${tipo}`)
                .then(res => res.json())
                .then(data => {
                    const ctx = document.getElementById('graficoPrincipal').getContext('2d');

                    if (graficoPrincipal) graficoPrincipal.destroy();

                    graficoPrincipal = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels || [],
                            datasets: [{
                                label: tipo.charAt(0).toUpperCase() + tipo.slice(1),
                                data: data.data || [],
                                borderColor: '#0B5ED7',
                                backgroundColor: 'rgba(11, 94, 215, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#e9ecef' } },
                                x: { grid: { color: '#e9ecef' } }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        function cargarGraficoEstados() {
            fetch("{{ route('admin.reportes.grafico') }}?tipo=estado_prestamos")
                .then(res => res.json())
                .then(data => {
                    const ctx = document.getElementById('graficoEstados').getContext('2d');

                    if (graficoEstados) graficoEstados.destroy();

                    graficoEstados = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: data.labels || ['Activos', 'Completados', 'Pendientes'],
                            datasets: [{
                                data: data.data || [0, 0, 0],
                                backgroundColor: ['#28a745', '#0dcaf0', '#ffc107', '#dc3545']
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true } }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        function cargarTopLibros() {
            fetch("{{ route('admin.reportes.grafico') }}?tipo=top_libros&limit=5")
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('topLibros');
                    let html = '';

                    if (data.data && data.data.length > 0) {
                        data.data.forEach((libro, index) => {
                            html += `
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <span class="badge bg-primary me-2">${index + 1}</span>
                                        <strong class="text-truncate">${libro.titulo || 'Sin título'}</strong>
                                        <br><small class="text-muted">${libro.autor || 'Sin autor'}</small>
                                    </div>
                                    <span class="badge bg-success">${libro.prestamos || 0}</span>
                                </div>
                            `;
                        });
                    } else {
                        html = '<p class="text-muted">No hay datos disponibles</p>';
                    }

                    container.innerHTML = html;
                })
                .catch(error => console.error('Error:', error));
        }

        function generarReporte() {
            const tipo = document.getElementById('tipoReporte').value;
            const inicio = document.getElementById('fechaInicio').value;
            const fin = document.getElementById('fechaFin').value;

            fetch(`{{ route('admin.reportes.tabla') }}?tipo=${tipo}&inicio=${inicio}&fin=${fin}`)
                .then(res => res.json())
                .then(data => {
                    const thead = document.getElementById('theadReporte');
                    const tbody = document.querySelector('#tablaReporte tbody');

                    if (!data.resultados || data.resultados.length === 0) {
                        document.getElementById('reporteResultado').style.display = 'none';
                        document.getElementById('reporteVacio').style.display = 'block';
                        return;
                    }

                    document.getElementById('reporteResultado').style.display = 'block';
                    document.getElementById('reporteVacio').style.display = 'none';

                    // Headers dinámicos según tipo
                    let headers = '';
                    if (tipo === 'prestamos') {
                        headers = '<tr><th>#</th><th>Libro</th><th>Usuario</th><th>Fecha</th><th>Estado</th></tr>';
                    } else if (tipo === 'usuarios') {
                        headers = '<tr><th>#</th><th>Nombre</th><th>Email</th><th>Registro</th></tr>';
                    } else if (tipo === 'devoluciones') {
                        headers = '<tr><th>#</th><th>Libro</th><th>Usuario</th><th>Fecha</th><th>Estado</th></tr>';
                    } else if (tipo === 'libros') {
                        headers = '<tr><th>#</th><th>Libro</th><th>Autor</th><th>Préstamos</th></tr>';
                    } else if (tipo === 'sanciones') {
                        headers = '<tr><th>#</th><th>Usuario</th><th>Días</th><th>Inicio</th><th>Fin</th></tr>';
                    } else if (tipo === 'inventario') {
                        headers = '<tr><th>#</th><th>Código</th><th>Título</th><th>Autor</th><th>Categoría</th><th>Cantidad</th><th>Disponibles</th><th>Estado</th></tr>';
                    }
                    thead.innerHTML = headers;

                    // Contenido de la tabla
                    let rows = '';
                    data.resultados.forEach((item, index) => {
                        if (tipo === 'prestamos') {
                            rows += `<tr><td>${index + 1}</td><td>${item.libro}</td><td>${item.usuario}</td><td>${item.fecha}</td><td>${item.estado}</td></tr>`;
                        } else if (tipo === 'usuarios') {
                            rows += `<tr><td>${index + 1}</td><td>${item.nombre} ${item.apellido}</td><td>${item.email}</td><td>${item.fecha}</td></tr>`;
                        } else if (tipo === 'devoluciones') {
                            rows += `<tr><td>${index + 1}</td><td>${item.libro}</td><td>${item.usuario}</td><td>${item.fecha}</td><td>${item.estado}</td></tr>`;
                        } else if (tipo === 'libros') {
                            rows += `<tr><td>${index + 1}</td><td>${item.libro}</td><td>${item.autor}</td><td>${item.total}</td></tr>`;
                        } else if (tipo === 'sanciones') {
                            rows += `<tr><td>${index + 1}</td><td>${item.usuario}</td><td>${item.dias}</td><td>${item.inicio}</td><td>${item.fin}</td></tr>`;
                        } else if (tipo === 'inventario') {
                            rows += `<tr><td>${index + 1}</td><td>${item.codigo}</td><td>${item.titulo}</td><td>${item.autor}</td><td>${item.categoria}</td><td>${item.cantidad}</td><td>${item.disponibles}</td><td>${item.estado}</td></tr>`;
                        }
                    });
                    tbody.innerHTML = rows;
                })
                .catch(error => console.error('Error:', error));
        }

        function actualizarDatos() {
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-arrow-clockwise spin me-1"></i>Actualizando...';
            btn.disabled = true;

            cargarDatos();

            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 2000);
        }

        function exportarDatos() {
            // Abrir el modal de exportar en lugar de exportar directamente
            const modal = new bootstrap.Modal(document.getElementById('modalExportar'));

            // Pre-llenar el tipo basado en el gráfico actual si está disponible
            const tipoGrafico = document.getElementById('tipoGrafico');
            if (tipoGrafico) {
                const tipoActual = tipoGrafico.value;
                // Mapear tipos de gráfico a tipos de reporte
                const mapeoTipos = {
                    'mensual': 'prestamos',
                    'semanal': 'prestamos',
                    'anual': 'prestamos'
                };
                const tipoReporte = mapeoTipos[tipoActual] || 'prestamos';
                document.getElementById('export_tipo').value = tipoReporte;
            }

            modal.show();
        }

        // Modo oscuro
        document.getElementById('toggleTheme').addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            this.innerHTML = isDark ? '<i class="bi bi-sun"></i>' : '<i class="bi bi-moon-stars"></i>';
            localStorage.setItem('dashboardTheme', isDark ? 'dark' : 'light');
        });

        // Funciones para gestión de sanciones
        function cargarMetricasSanciones() {
            fetch("{{ route('admin.reportes.grafico') }}?tipo=metricas_sanciones")
                .then(res => res.json())
                .then(data => {
                    document.getElementById('sancionesActivasTotal').textContent = data.activas || 0;
                    document.getElementById('sancionesCumplidasMes').textContent = data.cumplidas_mes || 0;
                    document.getElementById('usuariosSancionadosTotal').textContent = data.usuarios_sancionados || 0;
                    document.getElementById('promedioDiasSancion').textContent = data.promedio_dias || 0;
                })
                .catch(error => console.error('Error:', error));
        }

        function cargarTablaSanciones(filtros = {}) {
            const params = new URLSearchParams({
                ...filtros,
                action: 'listar_sanciones'
            });

            fetch(`{{ route('admin.reportes.grafico') }}?${params}`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('cuerpoTablaSanciones');
                    if (!tbody) return;

                    let html = '';
                    if (data.sanciones && data.sanciones.length > 0) {
                        data.sanciones.forEach(sancion => {
                            const estadoBadge = sancion.estado === 'activa' ? 'bg-danger' :
                                               sancion.estado === 'cumplida' ? 'bg-success' : 'bg-secondary';

                            html += `
                                <tr>
                                    <td>
                                        <div>
                                            <strong>${sancion.usuario_nombre}</strong>
                                            <br><small class="text-muted">${sancion.usuario_email}</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">${sancion.tipo}</span></td>
                                    <td>${sancion.dias_bloqueo}</td>
                                    <td>${new Date(sancion.fecha_inicio).toLocaleDateString()}</td>
                                    <td>${new Date(sancion.fecha_fin).toLocaleDateString()}</td>
                                    <td><span class="badge ${estadoBadge}">${sancion.estado}</span></td>
                                    <td class="text-truncate" style="max-width: 200px;" title="${sancion.observaciones || ''}">${sancion.observaciones || '-'}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            ${sancion.estado === 'activa' ?
                                                `<button class="btn btn-outline-success" onclick="levantarSancion(${sancion.id})" title="Levantar sanción">
                                                    <i class="bi bi-check"></i>
                                                </button>` : ''
                                            }
                                            <button class="btn btn-outline-danger" onclick="eliminarSancion(${sancion.id})" title="Eliminar">
                                                <i class="bi bi-trash"></i                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        html = '<tr><td colspan="8" class="text-center text-muted">No hay sanciones registradas</td></tr>';
                    }

                    tbody.innerHTML = html;
                })
                .catch(error => console.error('Error:', error));
        }

        function filtrarSanciones() {
            const filtros = {
                usuario: document.getElementById('buscarUsuarioSancion').value,
                estado: document.getElementById('filtroEstadoSancion').value,
                tipo: document.getElementById('filtroTipoSancion').value
            };
            cargarTablaSanciones(filtros);
        }

        function levantarSancion(idSancion) {
            if (confirm('¿Está seguro de que desea levantar esta sanción?')) {
                fetch(`{{ url('admin/sanciones/levantar') }}/${idSancion}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessToast(data.message);
                        cargarTablaSanciones();
                        cargarMetricasSanciones();
                    } else {
                        showErrorToast('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorToast('Error al procesar la solicitud');
                });
            }
        }

        function eliminarSancion(idSancion) {
            if (confirm('¿Está seguro de que desea eliminar esta sanción?')) {
                fetch(`{{ url('admin/sanciones/eliminar') }}/${idSancion}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessToast(data.message);
                        cargarTablaSanciones();
                        cargarMetricasSanciones();
                    } else {
                        showErrorToast('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorToast('Error al procesar la solicitud');
                });
            }
        }

        function actualizarSanciones() {
            cargarMetricasSanciones();
            cargarTablaSanciones();
        }

        function exportarSanciones() {
            const filtros = {
                usuario: document.getElementById('buscarUsuarioSancion').value,
                estado: document.getElementById('filtroEstadoSancion').value,
                tipo: document.getElementById('filtroTipoSancion').value
            };

            const params = new URLSearchParams({
                ...filtros,
                tipo: 'sanciones',
                export: '1'
            });

            const link = document.createElement('a');
            link.href = `{{ route('admin.reportes.tabla') }}?${params}`;
            link.download = `sanciones_${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-download me-1"></i>Descargando...';
            setTimeout(() => {
                btn.innerHTML = originalText;
            }, 3000);
        }

        function abrirModalNuevaSancion() {
            const modal = new bootstrap.Modal(document.getElementById('modalNuevaSancion'));

            // Limpiar formulario
            document.getElementById('formNuevaSancion').reset();

            // Configurar tipos de sanción predefinidos
            document.getElementById('tipo_sancion_nueva').addEventListener('change', function() {
                const diasInput = document.getElementById('dias_sancion');
                const observacionesTextarea = document.getElementById('observaciones_sancion_nueva');

                switch(this.value) {
                    case 'retraso':
                        diasInput.value = 7;
                        observacionesTextarea.value = 'Sanción por retraso en la devolución de libro';
                        break;
                    case 'daño':
                        diasInput.value = 15;
                        observacionesTextarea.value = 'Sanción por daño causado al libro';
                        break;
                    case 'perdida':
                        diasInput.value = 30;
                        observacionesTextarea.value = 'Sanción por pérdida del libro';
                        break;
                    default:
                        diasInput.value = '';
                        observacionesTextarea.value = '';
                }
            });

            modal.show();
        }

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            // Restaurar tema
            if (localStorage.getItem('dashboardTheme') === 'dark') {
                document.body.classList.add('dark-mode');
                document.getElementById('toggleTheme').innerHTML = '<i class="bi bi-sun"></i>';
            }

            // Establecer fechas por defecto
            const fechaFin = new Date();
            const fechaInicio = new Date();
            fechaInicio.setMonth(fechaInicio.getMonth() - 1);

            document.getElementById('fechaInicio').value = fechaInicio.toISOString().split('T')[0];
            document.getElementById('fechaFin').value = fechaFin.toISOString().split('T')[0];

            // Cargar datos iniciales
            cargarDatos();

            // Mostrar toast de bienvenida (después de un pequeño delay para que se cargue la página)
            setTimeout(() => {
                showInfoToast('¡Bienvenido al Dashboard! Ahora las notificaciones no bloquearán la interfaz.', 3000);
            }, 1000);

            // Agregar listeners globales para todos los modales
            document.addEventListener('hidden.bs.modal', function(event) {
                // Restaurar scroll cuando cualquier modal se cierre
                setTimeout(() => {
                    restaurarScrollYLimpiarModales();
                }, 50);
            });

            // Listeners adicionales para eventos de teclado (ESC)
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    setTimeout(() => {
                        restaurarScrollYLimpiarModales();
                    }, 100);
                }
            });

            // Listener global para clicks en backdrops o fuera de modales
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('modal-backdrop') ||
                    event.target.classList.contains('modal') ||
                    event.target.closest('.modal-backdrop')) {
                    setTimeout(() => {
                        restaurarScrollYLimpiarModales();
                    }, 200);
                }
            });

            // Agregar atributo data-libro-id a las filas de la tabla para facilitar la búsqueda
            const filasLibros = document.querySelectorAll('#tablaLibros tbody tr');
            filasLibros.forEach(fila => {
                const btnEditar = fila.querySelector('.btn-warning[onclick*="abrirModalEditarLibro"]');
                if (btnEditar) {
                    const onclick = btnEditar.getAttribute('onclick');
                    const match = onclick.match(/abrirModalEditarLibro\((\d+)\)/);
                    if (match) {
                        fila.setAttribute('data-libro-id', match[1]);
                    }
                }
            });

            // Envío AJAX para agregar libro
            const libroForm = document.querySelector('#modalLibro form');
            if (libroForm) {
                libroForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfMeta ? csrfMeta.content : '';

                    if (!csrfToken) {
                        console.error('CSRF token no encontrado');
                        return;
                    }

                    const formData = new FormData(libroForm);
                    const submitBtn = libroForm.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...';

                    try {
                        const response = await fetch(libroForm.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            if (errorData.errors) {
                                let errorHtml = '<ul>';
                                for (const key in errorData.errors) {
                                    errorHtml += `<li>${errorData.errors[key][0]}</li>`;
                                }
                                errorHtml += '</ul>';
                                const errorDiv = document.getElementById('libroFormErrors');
                                errorDiv.innerHTML = errorHtml;
                                errorDiv.classList.remove('d-none');
                                throw new Error('Errores de validación');
                            }
                            throw new Error(errorData.message || 'Error en la solicitud');
                        }

                        document.getElementById('libroFormErrors').classList.add('d-none');
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalLibro'));
                        if (modal) modal.hide();
                        window.location.reload();

                    } catch (error) {
                        console.error('Error:', error);
                        if (error.message !== 'Errores de validación') {
                            showErrorToast('Error al guardar: ' + error.message);
                        }
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    }
                });
            }

            // Manejar envío del formulario de exportación
            const formExportar = document.getElementById('formExportar');
            if (formExportar) {
                formExportar.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // IMPORTANTE: Cerrar modal inmediatamente para evitar bloqueos de UI
                    // Cerrar modal inmediatamente y limpiar backdrop
                    const modalElement = document.getElementById('modalExportar');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Función para limpiar completamente el modal y restaurar el scroll
                    function limpiarModalCompletamente() {
                        restaurarScrollYLimpiarModales();
                    }

                    // Ejecutar limpieza inmediatamente y con delays para asegurar
                    limpiarModalCompletamente();
                    setTimeout(limpiarModalCompletamente, 50);
                    setTimeout(limpiarModalCompletamente, 200);
                    setTimeout(limpiarModalCompletamente, 500);

                    const tipo = document.getElementById('export_tipo').value;
                    const fechaInicio = document.getElementById('export_fecha_inicio').value;
                    const fechaFin = document.getElementById('export_fecha_fin').value;
                    const formato = document.getElementById('export_formato').value;

                    // Construir URL de exportación
                    const params = new URLSearchParams({
                        tipo: tipo,
                        export: '1',
                        formato: formato
                    });

                    if (fechaInicio) params.append('inicio', fechaInicio);
                    if (fechaFin) params.append('fin', fechaFin);

                    const url = `{{ route('admin.reportes.tabla') }}?${params.toString()}`;

                    // Mostrar mensaje de descarga
                    const btn = this.querySelector('button[type="submit"]');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="bi bi-download me-2"></i>Descargando...';
                    btn.disabled = true;

                    // Usar window.open como método más confiable para la descarga
                    const downloadWindow = window.open(url, '_blank');

                    // Si window.open falla, usar fetch como respaldo
                    if (!downloadWindow || downloadWindow.closed || typeof downloadWindow.closed === 'undefined') {
                        // Usar fetch para descargar el archivo
                        fetch(url, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/octet-stream'
                            }
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Error en la respuesta del servidor');
                                }
                                return response.blob();
                            })
                            .then(blob => {
                                // Crear enlace de descarga
                                const link = document.createElement('a');
                                const objectUrl = window.URL.createObjectURL(blob);
                                link.href = objectUrl;

                                // Obtener nombre del archivo
                                const filename = `reporte_${tipo}_${new Date().toISOString().split('T')[0]}.${formato === 'excel' ? 'xlsx' : 'csv'}`;
                                link.download = filename;

                                // Agregar al DOM temporalmente y hacer clic
                                document.body.appendChild(link);
                                link.click();

                                // Limpiar
                                document.body.removeChild(link);
                                window.URL.revokeObjectURL(objectUrl);

                                // Mostrar mensaje de éxito con delay
                                setTimeout(() => {
                                    showSuccessToast('Archivo descargado exitosamente');
                                }, 300);
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                // Mostrar error con delay
                                setTimeout(() => {
                                    showErrorToast('Error al descargar el archivo: ' + error.message);
                                }, 300);
                            })
                            .finally(() => {
                                // Restaurar botón
                                btn.innerHTML = originalText;
                                btn.disabled = false;
                            });
                    } else {
                        // Si window.open funcionó, solo restaurar el botón
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                            // Delay para el toast para asegurar que el modal se haya cerrado
                            setTimeout(() => {
                                showInfoToast('Descarga iniciada en nueva ventana');
                            }, 300);
                        }, 1000);
                    }
                });
            }

            // Establecer fechas por defecto en el modal al abrirlo
            const modalExportar = document.getElementById('modalExportar');
            if (modalExportar) {
                modalExportar.addEventListener('show.bs.modal', function() {
                    // Usar las mismas fechas del generador de reportes si están disponibles
                    const fechaInicio = document.getElementById('fechaInicio').value;
                    const fechaFin = document.getElementById('fechaFin').value;

                    if (fechaInicio) document.getElementById('export_fecha_inicio').value = fechaInicio;
                    if (fechaFin) document.getElementById('export_fecha_fin').value = fechaFin;
                });
            }
        });

        // Función para ver detalles del trabajador
        function verDetallesTrabajador(idTrabajador) {
            const modal = new bootstrap.Modal(document.getElementById('modalDetallesTrabajador'));
            const content = document.getElementById('detallesTrabajadorContent');

            // Mostrar loading
            content.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando detalles...</p>
                </div>
            `;

            modal.show();

            // Realizar petición AJAX
            fetch(`/administrador/trabajador-detalles/${idTrabajador}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const trabajador = data.trabajador;
                        content.innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-card p-3 mb-3 rounded bg-light">
                                        <h6 class="text-primary mb-3"><i class="bi bi-person-badge me-2"></i>Información Personal</h6>
                                        <p><strong>Nombre:</strong> ${trabajador.nombre || 'No especificado'}</p>
                                        <p><strong>Usuario:</strong> ${trabajador.usuario || 'No especificado'}</p>
                                        <p><strong>Email:</strong> ${trabajador.email || 'No especificado'}</p>
                                        <p><strong>DNI:</strong> ${trabajador.dni || 'No especificado'}</p>
                                        <p><strong>Teléfono:</strong> ${trabajador.telefono || 'No especificado'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card p-3 mb-3 rounded bg-light">
                                        <h6 class="text-success mb-3"><i class="bi bi-geo-alt me-2"></i>Información Adicional</h6>
                                        <p><strong>Dirección:</strong> ${trabajador.direccion || 'No especificada'}</p>
                                        <p><strong>Fecha de Registro:</strong> ${trabajador.fecha_registro ? new Date(trabajador.fecha_registro).toLocaleDateString() : 'No especificada'}</p>
                                        <p><strong>Rol:</strong> Bibliotecario</p>
                                        <p><strong>Estado:</strong> <span class="badge bg-success">Activo</span></p>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        content.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Error al cargar los detalles del trabajador.
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Error al cargar los detalles del trabajador.
                        </div>
                    `;
                });
        }

        // Función para ver detalles del usuario
        function verDetallesUsuario(idUsuario) {
            const modal = new bootstrap.Modal(document.getElementById('modalDetallesUsuario'));
            const content = document.getElementById('detallesUsuarioContent');

            // Mostrar loading
            content.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando detalles...</p>
                </div>
            `;

            modal.show();

            // Realizar petición AJAX
            fetch(`/administrador/usuario-detalles/${idUsuario}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const usuario = data.usuario;
                        content.innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-card p-3 mb-3 rounded bg-light">
                                        <h6 class="text-primary mb-3"><i class="bi bi-person-lines-fill me-2"></i>Información Personal</h6>
                                        <p><strong>Nombre:</strong> ${usuario.nombre || 'No especificado'}</p>
                                        <p><strong>Apellido:</strong> ${usuario.apellido || 'No especificado'}</p>
                                        <p><strong>Email:</strong> ${usuario.email || 'No especificado'}</p>
                                        <p><strong>DNI:</strong> ${usuario.dni || 'No especificado'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card p-3 mb-3 rounded bg-light">
                                        <h6 class="text-success mb-3"><i class="bi bi-mortarboard me-2"></i>Información Académica</h6>
                                        <p><strong>Código Estudiante:</strong> ${usuario.codigo_estudiante || 'No especificado'}</p>
                                        <p><strong>Fecha de Registro:</strong> ${usuario.fecha_registro ? new Date(usuario.fecha_registro).toLocaleDateString() : 'No especificada'}</p>
                                        <p><strong>Estado:</strong> <span class="badge bg-success">Activo</span></p>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        content.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Error al cargar los detalles del usuario.
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Error al cargar los detalles del usuario.
                        </div>
                    `;
                });
        }

        // Función de búsqueda para las tablas
        function search(tipo) {
            let input, filter, table, tr, td, i, txtValue;
            if (tipo === 'trabajadores') {
                input = document.getElementById("searchTrabajadores");
                // Busca el h4 con texto "Trabajadores"
                let h4 = Array.from(document.querySelectorAll('#panel-cuentas h4')).find(h => h.textContent.trim().toLowerCase() === 'trabajadores');
                if (!h4) return;
                table = h4.nextElementSibling.querySelector('table');
            } else if (tipo === 'usuarios') {
                input = document.getElementById("searchUsuarios");
                // Busca el h4 con texto "Usuarios"
                let h4 = Array.from(document.querySelectorAll('#panel-cuentas h4')).find(h => h.textContent.trim().toLowerCase() === 'usuarios');
                if (!h4) return;
                table = h4.nextElementSibling.querySelector('table');
            } else {
                return;
            }
            filter = input.value.toLowerCase();
            tr = table.getElementsByTagName("tr");
            for (i = 1; i < tr.length; i++) { // Empieza en 1 para saltar el thead
                let found = false;
                let tds = tr[i].getElementsByTagName("td");
                for (let j = 0; j < tds.length; j++) {
                    td = tds[j];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = found ? "" : "none";
            }
        }

        // Función para validar que disponibles no sea mayor que cantidad
        function validarDisponibles() {
            const cantidad = parseInt(document.getElementById('edit_cantidad').value) || 0;
            const disponibles = parseInt(document.getElementById('edit_disponibles').value) || 0;
            const inputDisponibles = document.getElementById('edit_disponibles');

            if (disponibles > cantidad) {
                inputDisponibles.value = cantidad;
                showErrorToast('Los libros disponibles no pueden ser más que la cantidad total');
            }
        }

        // Función para abrir modal de editar libro
        function abrirModalEditarLibro(libroId) {
            // Buscar el libro en la tabla actual
            const fila = document.querySelector(`tr[data-libro-id="${libroId}"]`);
            if (!fila) {
                // Si no encontramos la fila, hacemos una petición AJAX para obtener los datos
                fetch(`/admin/libros/${libroId}/edit-data`)
                    .then(response => response.json())
                    .then(libro => {
                        llenarFormularioEdicion(libro);
                        document.getElementById('formEditarLibro').action = `/admin/libros/${libroId}`;
                        new bootstrap.Modal(document.getElementById('modalEditarLibro')).show();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showErrorToast('Error al cargar los datos del libro');
                    });
                return;
            }

            // Extraer datos de la fila de la tabla
            const celdas = fila.getElementsByTagName('td');
            const libro = {
                id_libro: libroId,
                codigo: celdas[0].textContent.trim(),
                titulo: celdas[1].textContent.trim(),
                autor: celdas[2].textContent.trim(),
                categoria: celdas[3].textContent.trim(),
                cantidad: celdas[4].textContent.trim(),
                disponibles: celdas[5].textContent.trim(),
                estado: celdas[6].querySelector('.badge').textContent.toLowerCase().trim(),
                portada: celdas[7].querySelector('img') ? celdas[7].querySelector('img').src : null
            };

            llenarFormularioEdicion(libro);
            document.getElementById('formEditarLibro').action = `/admin/libros/${libroId}`;
            new bootstrap.Modal(document.getElementById('modalEditarLibro')).show();
        }

        function llenarFormularioEdicion(libro) {
            document.getElementById('edit_codigo').value = libro.codigo || '';
            document.getElementById('edit_titulo').value = libro.titulo || '';
            document.getElementById('edit_autor').value = libro.autor || '';
            document.getElementById('edit_anio_publicacion').value = libro.anio_publicacion || '';
            document.getElementById('edit_cantidad').value = libro.cantidad || '';
            document.getElementById('edit_disponibles').value = libro.disponibles || '';
            document.getElementById('edit_editorial').value = libro.editorial || '';

            // Seleccionar categoría
            if (libro.categoria_id) {
                document.getElementById('edit_categoria_id').value = libro.categoria_id;
            } else if (libro.categoria) {
                // Buscar por nombre de categoría
                const selectCategoria = document.getElementById('edit_categoria_id');
                for (let option of selectCategoria.options) {
                    if (option.textContent.trim() === libro.categoria) {
                        option.selected = true;
                        break;
                    }
                }
            }

            // Seleccionar estado
            const estado = libro.estado || 'disponible';
            document.getElementById('edit_estado').value = estado;

            // Mostrar portada actual si existe
            if (libro.portada) {
                document.getElementById('portada_actual').style.display = 'block';
                document.getElementById('img_portada_actual').src = libro.portada;
            } else {
                document.getElementById('portada_actual').style.display = 'none';
            }
        }

        // Manejar envío del formulario de edición
        document.getElementById('formEditarLibro').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const actionUrl = this.action;

            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cerrar modal
                    bootstrap.Modal.getInstance(document.getElementById('modalEditarLibro')).hide();

                    // Mostrar mensaje de éxito
                    showSuccessToast('Libro actualizado correctamente');

                    // Recargar la página para mostrar los cambios
                    location.reload();
                } else {
                    showErrorToast(data.message || 'Error al actualizar el libro');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorToast('Error al actualizar el libro');
            });
        });
    </script>

    <!-- Modal para Editar Libro -->
    <div class="modal fade" id="modalEditarLibro" tabindex="-1" aria-labelledby="modalEditarLibroLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(90deg, #0B5ED7 60%, #0B1C2B 100%); color: #fff;">
                    <h5 class="modal-title" id="modalEditarLibroLabel">
                        <i class="bi bi-pencil-square me-2"></i>Editar Libro
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarLibro" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="edit-form-row mb-3">
                            <div class="edit-form-col mb-3 mb-md-0">
                                <label for="edit_codigo" class="form-label">Código</label>
                                <input type="text" class="form-control" id="edit_codigo" name="codigo" required>
                            </div>
                            <div class="edit-form-col">
                                <label for="edit_titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="edit_titulo" name="titulo" required>
                            </div>
                        </div>
                        <div class="edit-form-row mb-3">
                            <div class="edit-form-col mb-3 mb-md-0">
                                <label for="edit_autor" class="form-label">Autor</label>
                                <input type="text" class="form-control" id="edit_autor" name="autor">
                            </div>
                            <div class="edit-form-col">
                                <label for="edit_editorial" class="form-label">Editorial</label>
                                <input type="text" class="form-control" id="edit_editorial" name="editorial">
                            </div>
                        </div>
                        <div class="edit-form-row mb-3">
                            <div class="edit-form-col mb-3 mb-md-0">
                                <label for="edit_anio_publicacion" class="form-label">Año de publicación</label>
                                <input type="number" class="form-control" id="edit_anio_publicacion" name="anio_publicacion">
                            </div>
                            <div class="edit-form-col">
                                <label for="edit_categoria_id" class="form-label">Categoría</label>
                                <select class="form-select" id="edit_categoria_id" name="categoria_id">
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="edit-form-row mb-3">
                            <div class="edit-form-col mb-3 mb-md-0">
                                <label for="edit_cantidad" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="edit_cantidad" name="cantidad" min="1" required onchange="validarDisponibles()">
                            </div>
                            <div class="edit-form-col">
                                <label for="edit_disponibles" class="form-label">Disponibles</label>
                                <input type="number" class="form-control" id="edit_disponibles" name="disponibles" min="0" required onchange="validarDisponibles()">
                            </div>
                        </div>
                        <div class="edit-form-row mb-3">
                            <div class="edit-form-col mb-3 mb-md-0">
                                <label for="edit_estado" class="form-label">Estado</label>
                                <select class="form-select" id="edit_estado" name="estado" required>
                                    <option value="disponible">Disponible</option>
                                    <option value="prestado">Prestado</option>
                                    <option value="dañado">Dañado</option>
                                </select>
                            </div>
                            <div class="edit-form-col">
                                <label for="edit_portada" class="form-label">Portada</label>
                                <div id="portada_actual" class="mb-2 text-center" style="display: none;">
                                    <img id="img_portada_actual" src="" alt="Portada actual" width="90" class="rounded shadow-sm">
                                </div>
                                <input type="file" class="form-control" id="edit_portada" name="portada" accept="image/*">
                                <small class="text-muted">Dejar vacío para mantener la portada actual</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Actualizar Libro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Exportar Reportes -->
    <div class="modal fade" id="modalExportar" tabindex="-1" aria-labelledby="modalExportarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalExportarLabel">
                        <i class="bi bi-download me-2"></i>Exportar Reporte
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formExportar">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="export_tipo" class="form-label">Tipo de Reporte</label>
                            <select class="form-select" id="export_tipo" name="tipo" required>
                                <option value="prestamos">Préstamos</option>
                                <option value="devoluciones">Devoluciones</option>
                                <option value="usuarios">Usuarios Registrados</option>
                                <option value="sanciones">Sanciones</option>
                                <option value="libros">Libros más Solicitados</option>
                                <option value="inventario">Inventario de Libros</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="export_fecha_inicio" class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" id="export_fecha_inicio" name="fecha_inicio">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="export_fecha_fin" class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" id="export_fecha_fin" name="fecha_fin">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="export_formato" class="form-label">Formato de Exportación</label>
                            <select class="form-select" id="export_formato" name="formato">
                                <option value="excel">Excel (.xlsx)</option>
                                <option value="csv">CSV (.csv)</option>
                            </select>
                        </div>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Nota:</strong> Si no selecciona fechas, se exportarán todos los registros disponibles.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-download me-2"></i>Exportar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detalles Trabajador -->
    <div class="modal fade" id="modalDetallesTrabajador" tabindex="-1" aria-labelledby="modalDetallesTrabajadorLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title text-white" id="modalDetallesTrabajadorLabel">
                        <i class="bi bi-person-badge me-2"></i>Detalles del Trabajador
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detallesTrabajadorContent">
                    <div class="text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando detalles...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalles Usuario -->
    <div class="modal fade" id="modalDetallesUsuario" tabindex="-1" aria-labelledby="modalDetallesUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title text-white" id="modalDetallesUsuarioLabel">
                        <i class="bi bi-person-lines-fill me-2"></i>Detalles del Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detallesUsuarioContent">
                    <div class="text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando detalles...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <!-- Los toasts se insertarán aquí dinámicamente -->
    </div>

    <!-- Bootstrap JS para funcionalidad de alertas y modales -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
