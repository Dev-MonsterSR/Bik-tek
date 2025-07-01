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
        :root {
            --primary-gradient: linear-gradient(135deg, #0B1C2B 0%, #1a2332 100%);
            --accent-gradient: linear-gradient(90deg, #0B5ED7 0%, #198754 100%);
            --bg-light: #f8f9fa;
            --text-muted: #6c757d;
            --border-radius: 12px;
            --shadow-light: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-medium: 0 4px 20px rgba(0,0,0,0.12);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* ===== MOBILE-FIRST RESPONSIVE DESIGN ===== */

        /* Mobile Header */
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: var(--primary-gradient);
            color: white;
            z-index: 1100;
            padding: 0 16px;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-medium);
        }

        .mobile-header h5 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .mobile-menu-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            padding: 8px;
            border-radius: 8px;
            transition: var(--transition);
        }

        .mobile-menu-btn:hover {
            background: rgba(255,255,255,0.1);
        }

        /* Sidebar Responsive */
        .sidebar {
            min-height: 100vh;
            background: var(--primary-gradient);
            color: #fff;
            box-shadow: var(--shadow-medium);
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            z-index: 1050;
            overflow-y: auto;
            transition: var(--transition);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: var(--transition);
            padding-bottom: 80px; /* Space for mobile bottom nav */
        }

        /* Desktop spacing - ensure content doesn't stick to sidebar */
        @media (min-width: 769px) {
            .main-content {
                padding-left: 32px !important;
                padding-right: 24px !important;
            }

            /* Additional selector to override Bootstrap classes */
            main.main-content.col-md-10 {
                padding-left: 60px !important;
                padding-right: 40px !important;
            }

            /* Force spacing for any main content element */
            main[class*="main-content"] {
                padding-left: 32px !important;
                padding-right: 24px !important;
            }
        }

        /* Alternative approach - override Bootstrap's px-md-4 specifically */
        @media (min-width: 768px) {
            .main-content.px-md-4 {
                padding-left: 32px !important;
                padding-right: 24px !important;
            }
        }

        /* Mobile Bottom Navigation */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: white;
            border-top: 1px solid #e9ecef;
            z-index: 1000;
            padding: 8px 0;
            box-shadow: 0 -2px 12px rgba(0,0,0,0.1);
        }

        .bottom-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.75rem;
            padding: 4px;
            border-radius: 8px;
            transition: var(--transition);
        }

        .bottom-nav-item.active {
            color: #0B5ED7;
            background: rgba(11, 94, 215, 0.1);
        }

        .bottom-nav-item i {
            font-size: 1.2rem;
            margin-bottom: 2px;
        }

        /* Mobile Responsive Breakpoints */
        @media (max-width: 768px) {
            .mobile-header {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding-top: 60px;
                padding-left: 16px;
                padding-right: 16px;
            }

            .mobile-bottom-nav {
                display: flex;
            }

            .d-md-block {
                display: none !important;
            }

            .desktop-header {
                display: none !important;
            }
        }

        /* Sidebar Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
            opacity: 0;
            transition: var(--transition);
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        /* Sidebar Navigation Styles */
        .sidebar .nav-link {
            color: #e9ecef;
            font-weight: 500;
            transition: var(--transition);
            border-radius: 8px;
            margin: 4px 12px;
            padding: 12px 16px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: var(--accent-gradient);
            color: #fff;
            transform: translateX(4px);
            box-shadow: 0 4px 15px rgba(11, 94, 215, 0.3);
        }

        .sidebar .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Cards Responsive */
        .metric-card {
            border-radius: var(--border-radius);
            transition: var(--transition);
            overflow: hidden;
            position: relative;
            border: 0;
            background: #fff;
            box-shadow: var(--shadow-light);
            height: 100%;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .metric-card .card-body {
            padding: 1.5rem;
            position: relative;
        }

        .metric-icon {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.15;
            font-size: 2.5rem;
        }

        /* Mobile Responsive Cards */
        @media (max-width: 768px) {
            .metric-card .card-body {
                padding: 1rem;
                text-align: center;
            }

            .metric-card h3 {
                font-size: 1.5rem !important;
            }

            .metric-icon {
                display: none;
            }
        }

        /* Tables Responsive */
        .table-responsive {
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
        }

        .table {
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }

            .table th,
            .table td {
                padding: 0.5rem 0.25rem;
                vertical-align: middle;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* Chart Cards */
        .chart-card {
            border-radius: var(--border-radius);
            border: 0;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
        }

        .chart-card:hover {
            box-shadow: var(--shadow-medium);
        }

        /* Forms Responsive */
        @media (max-width: 768px) {
            .row.g-3 > .col-md-3,
            .row.g-3 > .col-md-4,
            .row.g-3 > .col-md-6 {
                margin-bottom: 0.75rem;
            }

            .edit-form-row {
                flex-direction: column;
                gap: 1rem;
            }

            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }

            .modal-body {
                padding: 1rem;
            }
        }

        /* Buttons Mobile Friendly */
        @media (max-width: 768px) {
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .btn-sm {
                padding: 0.375rem 0.75rem;
                font-size: 0.8rem;
            }

            .d-flex.gap-2 {
                flex-wrap: wrap;
                gap: 0.5rem !important;
            }
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 1200;
        }

        @media (max-width: 768px) {
            .toast-container {
                top: 70px;
                right: 10px;
                left: 10px;
            }
        }

        /* Dark Mode Enhancements */
        body.dark-mode {
            background: #1a1d23 !important;
            color: #f1f1f1 !important;
        }

        body.dark-mode .mobile-header {
            background: linear-gradient(135deg, #0B1C2B 0%, #1a2332 100%);
        }

        body.dark-mode .mobile-bottom-nav {
            background: #2d3238 !important;
            border-top-color: #444 !important;
        }

        body.dark-mode .bottom-nav-item {
            color: #adb5bd !important;
        }

        body.dark-mode .bottom-nav-item.active {
            color: #0B5ED7 !important;
            background: rgba(11, 94, 215, 0.2) !important;
        }

        body.dark-mode .metric-card,
        body.dark-mode .chart-card,
        body.dark-mode .card {
            background: #2d3238 !important;
            color: #f1f1f1 !important;
            border-color: #444 !important;
        }

        body.dark-mode .table {
            color: #f1f1f1 !important;
        }

        body.dark-mode .table-light {
            background-color: #3a4148 !important;
            color: #f1f1f1 !important;
        }

        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background-color: #3a4148 !important;
            color: #f1f1f1 !important;
            border-color: #555 !important;
        }

        body.dark-mode .modal-content {
            background-color: #2d3238 !important;
            color: #f1f1f1 !important;
            border-color: #444 !important;
        }

        /* Estilos específicos para modo oscuro - Cards métricas */
        body.dark-mode .metric-card .card-body {
            background: transparent !important;
        }

        /* Alerts en modo oscuro */
        body.dark-mode .alert-warning {
            background-color: #664d03 !important;
            border-color: #997404 !important;
            color: #ffecb3 !important;
        }

        body.dark-mode .alert-danger {
            background-color: #721c24 !important;
            border-color: #a52834 !important;
            color: #f8d7da !important;
        }

        body.dark-mode .alert-info {
            background-color: #055160 !important;
            border-color: #087990 !important;
            color: #b6effb !important;
        }

        body.dark-mode .alert-success {
            background-color: #051b11 !important;
            border-color: #0a3622 !important;
            color: #a3cfbb !important;
        }

        /* Badges en modo oscuro */
        body.dark-mode .badge.bg-success {
            background-color: #198754 !important;
        }

        body.dark-mode .badge.bg-danger {
            background-color: #dc3545 !important;
        }

        body.dark-mode .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        body.dark-mode .badge.bg-info {
            background-color: #0dcaf0 !important;
            color: #000 !important;
        }

        body.dark-mode .badge.bg-primary {
            background-color: #0d6efd !important;
        }

        /* Progress bars en modo oscuro */
        body.dark-mode .progress {
            background-color: #495057 !important;
        }

        /* Card headers en modo oscuro */
        body.dark-mode .card-header {
            background-color: #3a4148 !important;
            border-bottom-color: #555 !important;
            color: #f1f1f1 !important;
        }

        body.dark-mode .card-header.bg-white {
            background-color: #3a4148 !important;
        }

        /* Text muted en modo oscuro */
        body.dark-mode .text-muted {
            color: #adb5bd !important;
        }

        /* Borders en modo oscuro */
        body.dark-mode .border-bottom {
            border-bottom-color: #555 !important;
        }

        /* Dropdown en modo oscuro */
        body.dark-mode .dropdown-menu {
            background-color: #2d3238 !important;
            border-color: #444 !important;
        }

        body.dark-mode .dropdown-item {
            color: #f1f1f1 !important;
        }

        body.dark-mode .dropdown-item:hover,
        body.dark-mode .dropdown-item:focus {
            background-color: #3a4148 !important;
            color: #fff !important;
        }

        /* Tables específicas para modo oscuro */
        body.dark-mode .table-responsive {
            background-color: #2d3238 !important;
        }

        body.dark-mode .table thead th {
            background-color: #3a4148 !important;
            color: #f1f1f1 !important;
            border-color: #555 !important;
        }

        body.dark-mode .table tbody tr {
            background-color: #2d3238 !important;
        }

        body.dark-mode .table tbody tr:hover {
            background-color: #3a4148 !important;
        }

        body.dark-mode .table td,
        body.dark-mode .table th {
            border-color: #555 !important;
        }

        /* Botones en modo oscuro */
        body.dark-mode .btn-outline-primary {
            color: #6ea8fe !important;
            border-color: #6ea8fe !important;
        }

        body.dark-mode .btn-outline-primary:hover {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
        }

        body.dark-mode .btn-outline-secondary {
            color: #adb5bd !important;
            border-color: #6c757d !important;
        }

        body.dark-mode .btn-outline-secondary:hover {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
        }

        /* Input group en modo oscuro */
        body.dark-mode .input-group-text {
            background-color: #3a4148 !important;
            border-color: #555 !important;
            color: #f1f1f1 !important;
        }

        /* Charts containers en modo oscuro */
        body.dark-mode canvas {
            filter: brightness(0.9);
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeInUp 0.4s ease-out;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        /* Additional Mobile Optimizations */
        @media (max-width: 576px) {
            .container-fluid {
                padding: 0;
            }

            .main-content {
                padding-left: 8px;
                padding-right: 8px;
            }

            .metric-card .card-body h3 {
                font-size: 1.25rem !important;
            }

            .metric-card .card-body p {
                font-size: 0.875rem;
            }

            .chart-card .card-body {
                padding: 0.75rem;
            }
        }

        /* Loading States */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* Estilos para modal de edición */
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

        /* Estilos para modales de detalles */
        .info-card {
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        body.dark-mode .info-card {
            background-color: #3a4148 !important;
            color: #f1f1f1 !important;
            border-color: #555 !important;
        }
    </style>
</head>
<body>
    <!-- Mobile Header -->
    <div class="mobile-header">
        <button class="mobile-menu-btn" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <h5>Biblioteca Admin</h5>
        <!-- Espacio para mantener el layout centrado -->
        <div style="width: 48px;"></div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 sidebar py-4" id="sidebar">
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
            <main class="col-md-10 ms-sm-auto py-3 main-content">
                <!-- Desktop Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 desktop-header">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Dashboard Ejecutivo</h1>
                        <p class="text-muted mb-0">Análisis completo del sistema bibliotecario</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalExportar">
                            <i class="bi bi-download me-1"></i><span class="d-none d-sm-inline">Exportar</span>
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="actualizarDatos()">
                            <i class="bi bi-arrow-clockwise me-1"></i><span class="d-none d-sm-inline">Actualizar</span>
                        </button>
                        <button id="toggleTheme" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile Header Content -->
                <div class="d-md-none mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h4 fw-bold mb-1">Dashboard</h2>
                            <small class="text-muted">Sistema bibliotecario</small>
                        </div>
                        <div class="d-flex gap-1">
                            <button class="btn btn-outline-primary btn-sm" onclick="actualizarDatos()">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalExportar">
                                <i class="bi bi-download"></i>
                            </button>
                        </div>
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
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
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

                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
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

                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
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

                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
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

                    <!-- Gráficos Responsive -->
                    <div class="row mb-4">
                        <div class="col-lg-8 col-md-12 mb-3 mb-lg-0">
                            <div class="chart-card">
                                <div class="card-header bg-white border-0 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                                    <div class="mb-2 mb-sm-0">
                                        <h6 class="fw-bold mb-1">Actividad del Sistema</h6>
                                        <small class="text-muted">Últimos 30 días</small>
                                    </div>
                                    <select class="form-select form-select-sm" id="tipoGrafico" onchange="actualizarGrafico()" style="width: auto; min-width: 140px;">
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

                        <div class="col-lg-4 col-md-12">
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

                    <!-- Estadísticas Responsive -->
                    <div class="row mb-4">
                        <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                            <div class="chart-card h-100">
                                <div class="card-header bg-white border-0">
                                    <h6 class="fw-bold mb-1">Indicadores del Sistema</h6>
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

                        <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
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

                        <div class="col-lg-4 col-md-12">
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

                    <!-- Generador de Reportes Responsive -->
                    <div class="chart-card mb-4">
                        <div class="card-header bg-white border-0">
                            <h6 class="fw-bold mb-1">Generador de Reportes</h6>
                        </div>
                        <div class="card-body">
                            <form class="row g-3" onsubmit="event.preventDefault(); generarReporte();">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <label class="form-label small d-md-none">Tipo de Reporte</label>
                                    <select class="form-select" id="tipoReporte">
                                        <option value="prestamos">Préstamos</option>
                                        <option value="devoluciones">Devoluciones</option>
                                        <option value="usuarios">Usuarios</option>
                                        <option value="sanciones">Sanciones</option>
                                        <option value="libros">Libros más solicitados</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <label class="form-label small d-md-none">Fecha Inicio</label>
                                    <input type="date" class="form-control" id="fechaInicio" placeholder="Fecha inicio">
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <label class="form-label small d-md-none">Fecha Fin</label>
                                    <input type="date" class="form-control" id="fechaFin" placeholder="Fecha fin">
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-table me-1"></i><span class="d-none d-sm-inline">Generar </span>Reporte
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

                <!-- Panel Cuentas Responsive -->
                <div id="panel-cuentas" style="display:none;">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                        <div class="mb-3 mb-md-0">
                            <h2 class="fw-bold h3">Gestión de Cuentas</h2>
                            <p class="text-muted mb-0 d-none d-md-block">Administra bibliotecarios y usuarios</p>
                        </div>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTrabajador">
                            <i class="bi bi-person-plus me-2"></i>Agregar Bibliotecario
                        </button>
                    </div>

                    <!-- Filtros de búsqueda responsive -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
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

                    <!-- Sección Trabajadores -->
                    <div class="chart-card mb-4">
                        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                            <h4 class="h6 fw-bold mb-0">Bibliotecarios</h4>
                            <span class="badge bg-primary">{{ count($trabajadores) }}</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Nombre</th>
                                        <th class="d-none d-md-table-cell">Rol</th>
                                        <th class="d-none d-lg-table-cell">Email</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trabajadores as $trabajador)
                                        <tr>
                                            <td>
                                                <strong class="d-block">{{ $trabajador->usuario }}</strong>
                                                <small class="text-muted d-md-none">{{ $trabajador->email }}</small>
                                            </td>
                                            <td>{{ $trabajador->nombre }}</td>
                                            <td class="d-none d-md-table-cell">
                                                <span class="badge bg-info">Bibliotecario</span>
                                            </td>
                                            <td class="d-none d-lg-table-cell">{{ $trabajador->email }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button class="btn btn-sm btn-info" onclick="verDetallesTrabajador({{ $trabajador->id_trabajador }})" title="Ver detalles">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <form action="{{ route('trabajadores.destroy', $trabajador) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta cuenta?')" title="Eliminar">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sección Usuarios -->
                    <div class="chart-card mb-4">
                        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                            <h4 class="h6 fw-bold mb-0">Usuarios</h4>
                            <span class="badge bg-success">{{ count($usuarios) }}</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th class="d-none d-md-table-cell">Apellido</th>
                                        <th class="d-none d-lg-table-cell">Email</th>
                                        <th class="d-none d-xl-table-cell">Fecha Registro</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usuarios as $usuario)
                                        <tr>
                                            <td>
                                                <strong class="d-block">{{ $usuario->nombre }} {{ $usuario->apellido }}</strong>
                                                <small class="text-muted d-md-none">{{ $usuario->email }}</small>
                                                <small class="text-muted d-xl-none d-block">{{ date('d/m/Y', strtotime($usuario->fecha_registro)) }}</small>
                                            </td>
                                            <td class="d-none d-md-table-cell">{{ $usuario->apellido }}</td>
                                            <td class="d-none d-lg-table-cell">{{ $usuario->email }}</td>
                                            <td class="d-none d-xl-table-cell">{{ date('d/m/Y', strtotime($usuario->fecha_registro)) }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button class="btn btn-sm btn-info" onclick="verDetallesUsuario({{ $usuario->id_usuario }})" title="Ver detalles">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta cuenta?')" title="Eliminar">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="#" class="bottom-nav-item active" onclick="showTabMobile('dashboard')">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="#" class="bottom-nav-item" onclick="showTabMobile('inventario')">
            <i class="bi bi-book"></i>
            <span>Inventario</span>
        </a>
        <a href="#" class="bottom-nav-item" onclick="showTabMobile('cuentas')">
            <i class="bi bi-people"></i>
            <span>Cuentas</span>
        </a>
        <a href="#" class="bottom-nav-item" id="toggleThemeMobile" onclick="toggleThemeMain()">
            <i class="bi bi-moon-stars"></i>
            <span>Tema</span>
        </a>
        <a href="{{ route('inicio') }}" class="bottom-nav-item">
            <i class="bi bi-house"></i>
            <span>Inicio</span>
        </a>
    </nav>

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
        // ===== FUNCIONES MOBILE-FIRST =====

        // Toggle Sidebar para Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        // Cerrar Sidebar
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }

        // Función mejorada para cambiar tabs (compatible con mobile)
        function showTab(tabName) {
            // Cerrar sidebar en mobile
            if (window.innerWidth <= 768) {
                closeSidebar();
            }

            // Ocultar todos los paneles
            const panels = ['dashboard', 'inventario', 'cuentas', 'sanciones'];
            panels.forEach(panel => {
                const element = document.getElementById(`panel-${panel}`);
                if (element) {
                    element.style.display = 'none';
                }

                // Actualizar clases activas en sidebar
                const tabElement = document.getElementById(`tab-${panel}`);
                if (tabElement) {
                    tabElement.classList.remove('active');
                }
            });

            // Mostrar el panel seleccionado
            const selectedPanel = document.getElementById(`panel-${tabName}`);
            if (selectedPanel) {
                selectedPanel.style.display = 'block';
                selectedPanel.classList.add('fade-in');
            }

            // Activar tab en sidebar
            const selectedTab = document.getElementById(`tab-${tabName}`);
            if (selectedTab) {
                selectedTab.classList.add('active');
            }

            // Actualizar bottom navigation en mobile
            updateBottomNavigation(tabName);
        }

        // Función específica para mobile bottom navigation
        function showTabMobile(tabName) {
            showTab(tabName);
        }

        // Actualizar estado de bottom navigation
        function updateBottomNavigation(activeTab) {
            const bottomNavItems = document.querySelectorAll('.bottom-nav-item');
            bottomNavItems.forEach(item => {
                item.classList.remove('active');
            });

            // Encontrar y activar el item correspondiente
            const tabMap = {
                'dashboard': 0,
                'inventario': 1,
                'cuentas': 2
            };

            const index = tabMap[activeTab];
            if (index !== undefined && bottomNavItems[index]) {
                bottomNavItems[index].classList.add('active');
            }
        }

        // Responsive handling
        function handleResize() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            if (window.innerWidth > 768) {
                // Desktop: asegurar que sidebar esté visible
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        }

        // Event listeners para funcionalidad móvil
        window.addEventListener('resize', handleResize);
        window.addEventListener('orientationchange', function() {
            setTimeout(handleResize, 100);
        });

        // Touch gestures para sidebar (opcional)
        let startX = 0;
        let currentX = 0;
        let isDragging = false;

        document.addEventListener('touchstart', function(e) {
            if (e.touches[0].clientX < 20) {
                startX = e.touches[0].clientX;
                isDragging = true;
            }
        });

        document.addEventListener('touchmove', function(e) {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
        });

        document.addEventListener('touchend', function(e) {
            if (!isDragging) return;

            const deltaX = currentX - startX;
            if (deltaX > 50 && window.innerWidth <= 768) {
                toggleSidebar();
            }

            isDragging = false;
        });

        // Modo oscuro mejorado con sincronización
        function initializeTheme() {
            const savedTheme = localStorage.getItem('dashboardTheme') || 'light';
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-mode');
                updateThemeButtons('dark');
            }
        }

        function toggleThemeMain() {
            const body = document.body;
            const isDark = body.classList.contains('dark-mode');

            if (isDark) {
                body.classList.remove('dark-mode');
                localStorage.setItem('dashboardTheme', 'light');
                updateThemeButtons('light');
            } else {
                body.classList.add('dark-mode');
                localStorage.setItem('dashboardTheme', 'dark');
                updateThemeButtons('dark');
            }
        }

        function updateThemeButtons(theme) {
            const desktopBtn = document.getElementById('toggleTheme');
            const mobileBtn = document.getElementById('toggleThemeMobile');
            const icon = theme === 'dark' ? 'bi-sun' : 'bi-moon-stars';

            if (desktopBtn) {
                desktopBtn.innerHTML = `<i class="bi ${icon}"></i>`;
            }

            if (mobileBtn) {
                const iconElement = mobileBtn.querySelector('i');
                if (iconElement) {
                    iconElement.className = `bi ${icon}`;
                }
            }
        }

        // Event listeners para botones de tema
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar tema primero
            initializeTheme();

            // Luego agregar listeners
            const desktopThemeBtn = document.getElementById('toggleTheme');

            if (desktopThemeBtn) {
                // Asegurar que no hay listeners duplicados
                desktopThemeBtn.removeEventListener('click', toggleThemeMain);
                desktopThemeBtn.addEventListener('click', toggleThemeMain);
            }
        });

        // ===== FIN FUNCIONES MOBILE-FIRST =====
    </script>

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

            // Agregar listener para limpiar el modal cuando se cierre
            const modalEditarLibro = document.getElementById('modalEditarLibro');
            if (modalEditarLibro) {
                modalEditarLibro.addEventListener('hidden.bs.modal', function() {
                    // Solo limpiar cuando realmente se cierra, no cuando se abre
                    setTimeout(() => {
                        document.getElementById('formEditarLibro').reset();
                        document.getElementById('portada_actual').style.display = 'none';
                        restaurarScrollYLimpiarModales();
                    }, 100);
                });

                // Prevenir reset automático al mostrar el modal
                modalEditarLibro.addEventListener('show.bs.modal', function() {
                    console.log('Modal de edición abriéndose...');
                });

                modalEditarLibro.addEventListener('shown.bs.modal', function() {
                    console.log('Modal de edición completamente abierto');
                });
            }

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
            // Limpiar formulario antes de llenarlo
            document.getElementById('formEditarLibro').reset();
            document.getElementById('portada_actual').style.display = 'none';

            // Hacer petición AJAX para obtener los datos completos del libro
            fetch(`/admin/libros/${libroId}/edit-data`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener los datos del libro');
                    }
                    return response.json();
                })
                .then(libro => {
                    llenarFormularioEdicion(libro);
                    document.getElementById('formEditarLibro').action = `/admin/libros/${libroId}`;
                    new bootstrap.Modal(document.getElementById('modalEditarLibro')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorToast('Error al cargar los datos del libro: ' + error.message);
                });
        }

        function llenarFormularioEdicion(libro) {
            console.log('Datos del libro recibidos:', libro); // Para debugging

            // Llenar todos los campos del formulario con verificación
            const campos = [
                { id: 'edit_codigo', valor: libro.codigo },
                { id: 'edit_titulo', valor: libro.titulo },
                { id: 'edit_autor', valor: libro.autor },
                { id: 'edit_anio_publicacion', valor: libro.anio_publicacion },
                { id: 'edit_cantidad', valor: libro.cantidad },
                { id: 'edit_disponibles', valor: libro.disponibles },
                { id: 'edit_editorial', valor: libro.editorial }
            ];

            campos.forEach(campo => {
                const elemento = document.getElementById(campo.id);
                if (elemento) {
                    elemento.value = campo.valor || '';
                    console.log(`Campo ${campo.id} establecido a:`, elemento.value);
                } else {
                    console.error(`No se encontró el elemento con ID: ${campo.id}`);
                }
            });

            // Seleccionar categoría
            if (libro.categoria_id) {
                const selectCategoria = document.getElementById('edit_categoria_id');
                if (selectCategoria) {
                    selectCategoria.value = libro.categoria_id;
                    console.log('Categoría seleccionada:', libro.categoria_id);
                }
            }

            // Seleccionar estado
            const estado = libro.estado || 'disponible';
            const selectEstado = document.getElementById('edit_estado');
            if (selectEstado) {
                selectEstado.value = estado;
                console.log('Estado seleccionado:', estado);
            }

            // Mostrar portada actual si existe
            if (libro.portada) {
                document.getElementById('portada_actual').style.display = 'block';
                document.getElementById('img_portada_actual').src = libro.portada;
            } else {
                document.getElementById('portada_actual').style.display = 'none';
            }

            console.log('Formulario completamente llenado'); // Para debugging
        }

        // Manejar envío del formulario de edición
        document.getElementById('formEditarLibro').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const actionUrl = this.action;
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // Deshabilitar botón y mostrar loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Actualizando...';

            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Error en la solicitud');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Cerrar modal
                    bootstrap.Modal.getInstance(document.getElementById('modalEditarLibro')).hide();

                    // Mostrar mensaje de éxito
                    showSuccessToast('Libro actualizado correctamente');

                    // Recargar la página para mostrar los cambios
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showErrorToast(data.message || 'Error al actualizar el libro');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorToast('Error al actualizar el libro: ' + error.message);
            })
            .finally(() => {
                // Restaurar botón
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
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
                                <input type="number" class="form-control" id="edit_anio_publicacion" name="anio_publicacion" min="1000" max="2099">
                            </div>
                            <div class="edit-form-col">
                                <label for="edit_categoria_id" class="form-label">Categoría</label>
                                <select class="form-select" id="edit_categoria_id" name="categoria_id">
                                    <option value="">Seleccionar categoría</option>
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
