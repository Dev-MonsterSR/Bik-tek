{{-- Redirección si no hay sesión --}}
@if(!session('trabajador_id'))
    <script>window.location = "{{ route('login') }}";</script>
    @php exit; @endphp
@endif

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Bibliotecario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            min-height: 100vh;
            background: #0B1C2B;
            color: #fff;
        }

        /* Comportamiento sticky para desktop */
        @media (min-width: 768px) {
            .sidebar {
                position: sticky;
                top: 0;
                height: 100vh;
                overflow-y: auto;
            }

            /* Asegurar que el layout funcione correctamente */
            .container-fluid .row {
                align-items: flex-start;
            }
        }
        .sidebar .nav-link {
            color: #fff;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 4px;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #0B5ED7;
            color: #fff;
        }
        .sidebar .nav-link i {
            margin-right: 8px;
        }
        .sidebar .sidebar-header {
            padding: 1.5rem 1rem 1rem 1rem;
            text-align: center;
        }
        .sidebar .sidebar-header img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            margin-bottom: 0.5rem;
        }
        .content {
            padding: 2rem 1rem;
        }

        /* Estilos responsive para el layout */
        @media (max-width: 767.98px) {
            .content {
                padding: 1rem 0.75rem;
                margin-left: 0 !important;
            }
        }

        @media (min-width: 768px) {
            .container-fluid .row {
                align-items: flex-start;
            }

            .content {
                padding: 2rem 1rem;
            }
        }
        .table thead { background: #0B1C2B; color: #fff; }
        .modal-header { background: #0B5ED7; color: #fff; }
        /* Desktop: sidebar sticky */
        @media (min-width: 768px) {
            .sidebar {
                position: sticky;
                top: 0;
                height: 100vh;
                overflow-y: auto;
            }
        }

        @media (max-width: 767.98px) {
            /* Sidebar móvil: fijo y oculto por defecto */
            .sidebar {
                position: fixed !important;
                top: 0;
                left: -100%;
                width: 280px;
                height: 100vh;
                z-index: 1050;
                padding: 0.5rem 0;
                transition: left 0.3s ease;
                overflow-y: auto;
                box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            }
            .sidebar.show {
                left: 0;
            }
            .sidebar .nav-link {
                font-size: 1rem;
                padding: 0.75rem 1rem;
            }
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                background: rgba(0,0,0,0.5);
                z-index: 1040;
                display: none;
            }
            .sidebar-overlay.show {
                display: block;
            }
            .mobile-header {
                position: sticky;
                top: 0;
                left: 0;
                right: 0;
                background: #0B1C2B;
                color: white;
                padding: 1rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                z-index: 1030;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .mobile-menu-btn {
                background: none;
                border: none;
                color: white;
                font-size: 1.5rem;
                cursor: pointer;
            }
        }

        /* Estilos específicos para sanciones */
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.8rem;
        }

        /* Estilos para acordeones */
        .accordion-item {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem !important;
            overflow: hidden;
        }

        .accordion-button {
            background-color: #f8f9fa;
            border: none;
            padding: 1rem;
        }

        .accordion-button:not(.collapsed) {
            background-color: #e7f1ff;
            border-color: #86b7fe;
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            border-color: #86b7fe;
        }

        .accordion-button::after {
            flex-shrink: 0;
            width: 1.25rem;
            height: 1.25rem;
            margin-left: auto;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23212529'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-size: 1.25rem;
            transition: transform 0.2s ease-in-out;
        }

        .accordion-button:not(.collapsed)::after {
            transform: rotate(180deg);
        }

        .accordion-body {
            padding: 1rem;
            background-color: white;
        }

        .usuario-row:hover {
            background-color: #f8f9fa;
        }

        .badge {
            font-size: 0.75rem;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,.075);
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .modal-lg {
            max-width: 800px;
        }

        #buscarUsuario, #filtroSanciones {
            transition: all 0.3s ease;
        }

        #buscarUsuario:focus, #filtroSanciones:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        /* Nuevos estilos mejorados para el dashboard */

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            text-align: center;
            border: 1px solid #e9ecef;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        }

        /* Estilos para el acordeón de solicitudes */
        .accordion-item {
            border-radius: 12px;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }

        .accordion-button {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border: none;
            font-weight: 500;
            padding: 1rem 1.25rem;
            transition: all 0.3s ease;
        }

        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #e3f2fd 0%, #f5f5f5 100%);
            color: #0B5ED7;
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(11, 94, 215, 0.25);
        }

        .accordion-body {
            background: #fafafa;
            border-top: 1px solid #e9ecef;
        }

        .section-title {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding: 1rem 0;
            border-bottom: 2px solid #e9ecef;
        }

        .section-title i {
            font-size: 1.5rem;
            color: #0B5ED7;
            margin-right: 1rem;
        }

        .section-title h2 {
            margin: 0;
            color: #212529;
            font-weight: 600;
        }

        .search-box {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .card.border-warning {
            border-color: #ffc107 !important;
            border-width: 2px;
        }

        .card.border-info {
            border-color: #0dcaf0 !important;
            border-width: 2px;
        }

        .card.border-danger {
            border-color: #dc3545 !important;
            border-width: 2px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .accordion-button {
                padding: 0.75rem;
                font-size: 0.9rem;
            }

            .section-title {
                flex-direction: column;
                text-align: center;
                padding: 0.75rem 0.5rem;
                margin-bottom: 1rem;
            }

            .section-title i {
                margin-right: 0;
                margin-bottom: 0.5rem;
                font-size: 1.25rem;
            }

            .section-title h2 {
                font-size: 1.25rem;
            }

            /* Stats cards más pequeñas en móvil y en 2 columnas */
            .stats-card {
                padding: 0.75rem;
                margin-bottom: 0.5rem;
            }

            .stats-card h4 {
                font-size: 1.1rem;
                margin-bottom: 0.25rem;
            }

            .stats-card p {
                font-size: 0.7rem;
                margin-bottom: 0;
            }

            .stats-icon {
                width: 28px;
                height: 28px;
                font-size: 0.9rem;
                margin-bottom: 0.25rem;
            }

            /* Hacer que las estadísticas se muestren en 2 columnas en móvil */
            .row.mb-4 .col-md-3 {
                flex: 0 0 50%;
                max-width: 50%;
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }

            /* Reducir espaciado entre filas en móvil */
            .row.mb-4 {
                margin-bottom: 1rem !important;
                margin-left: -0.25rem;
                margin-right: -0.25rem;
            }

            /* Hacer la búsqueda más compacta */
            .search-box {
                padding: 1rem;
                margin-bottom: 0.75rem;
            }
        }

        /* Modo oscuro mejorado */
        .dark-theme {
            background-color: #1a1a1a !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .sidebar {
            background: #0a0a0a !important;
        }

        .dark-theme .mobile-header {
            background: #0a0a0a !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .stats-card {
            background: #2d2d2d !important;
            border: 1px solid #444 !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .stats-card:hover {
            background: #333 !important;
        }

        .dark-theme .section-title {
            color: #e0e0e0 !important;
            border-bottom-color: #444 !important;
        }

        .dark-theme .section-title i {
            color: #ccc !important;
        }

        .dark-theme .card {
            background: #2d2d2d !important;
            border: 1px solid #444 !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .table {
            color: #e0e0e0 !important;
        }

        .dark-theme .table thead th {
            background: #1a1a1a !important;
            border-color: #444 !important;
        }

        .dark-theme .table tbody td {
            border-color: #444 !important;
            background: #2d2d2d !important;
        }

        .dark-theme .table-hover tbody tr:hover {
            background: #333 !important;
        }

        .dark-theme .search-box {
            background: #2d2d2d !important;
            border: 1px solid #444 !important;
        }

        .dark-theme .form-control {
            background: #333 !important;
            border: 1px solid #555 !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .form-control:focus {
            background: #333 !important;
            border-color: #86b7fe !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .book-card {
            background: #2d2d2d !important;
            border: 1px solid #444 !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .book-card:hover {
            background: #333 !important;
        }

        /* Estilos para títulos de sección */
        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.5rem 1rem;
            border-bottom: 2px solid #e9ecef;
        }

        .section-title i {
            font-size: 1.5rem;
            color: #6c757d;
        }

        .stats-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-size: 1.2rem;
            color: white;
        }

        .stats-icon.prestamos { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
        .stats-icon.devoluciones { background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); }
        .stats-icon.sanciones { background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); }

        .table-modern {
            border-radius: 8px;
            overflow: hidden;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table-modern thead th {
            background: #495057;
            color: white;
            border: none;
            padding: 0.75rem;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .table-modern tbody td {
            padding: 0.75rem;
            border: none;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }

        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        .search-box {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1rem;
        }

        .search-box .form-control {
            border-radius: 6px;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }

        .search-box .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .action-btn {
            border-radius: 6px;
            padding: 0.25rem 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            font-size: 0.75rem;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.15);
        }

        .action-btn.btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .action-btn.btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        }

        .action-btn.btn-primary {
            background: linear-gradient(135deg, #0B5ED7 0%, #6f42c1 100%);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .status-badge.success {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.danger {
            background: #f8d7da;
            color: #721c24;
        }

        .status-badge.warning {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .book-card {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .book-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        }

        .book-icon {
            width: 35px;
            height: 35px;
            background: #6c757d;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
        }

        /* Estilos específicos para sanciones */
        .sanciones-header {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px 15px 0 0;
            margin-bottom: 0;
        }

        .sanciones-card {
            border-radius: 0 0 15px 15px;
            overflow: hidden;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .sanciones-card .card-header {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
            border: none;
            padding: 1rem 1.5rem;
        }

        .usuarios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .usuario-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.075);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .usuario-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .usuario-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .usuario-card.sancionado::before {
            background: linear-gradient(90deg, #dc3545 0%, #fd7e14 100%);
        }

        .usuario-info {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .usuario-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            margin-right: 1rem;
        }

        .usuario-datos h6 {
            margin: 0;
            font-weight: 600;
            color: #495057;
        }

        .usuario-email {
            color: #6c757d;
            font-size: 0.85rem;
        }

        .usuario-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-number {
            font-size: 1.25rem;
            font-weight: 700;
            color: #495057;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .usuario-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn-usuario {
            flex: 1;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.8rem;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-usuario:hover {
            transform: translateY(-1px);
        }

        /* Filtros mejorados */
        .filtros-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 2rem;
        }

        .filtros-row {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        .filtro-group {
            display: flex;
            flex-direction: column;
        }

        .filtro-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .filtro-input {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .filtro-input:focus {
            border-color: #0B5ED7;
            box-shadow: 0 0 0 0.2rem rgba(11, 94, 215, 0.25);
        }

        /* Modal mejorado */
        .modal-content {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #0B5ED7 0%, #0B1C2B 100%);
            color: white;
            border: none;
            padding: 1.5rem;
        }

        .modal-header.bg-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e83e8c 100%) !important;
            color: white !important;
        }

        .modal-header.bg-primary {
            background: linear-gradient(135deg, #0B5ED7 0%, #6f42c1 100%) !important;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #e9ecef;
        }

        /* Tabs mejorados */
        .nav-tabs {
            border: none;
            margin-bottom: 1.5rem;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 8px;
            margin-right: 0.5rem;
            background: #f8f9fa;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #0B5ED7 0%, #6f42c1 100%);
            color: white;
        }

        .nav-tabs .nav-link:hover {
            background: #e9ecef;
            color: #495057;
        }

        .nav-tabs .nav-link.active:hover {
            background: linear-gradient(135deg, #0B5ED7 0%, #6f42c1 100%);
            color: white;
        }

        /* Alerts mejorados */
        .alert {
            border: none;
            border-radius: 10px;
            border-left: 4px solid;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            border-left-color: #17a2b8;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left-color: #ffc107;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-left-color: #dc3545;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .book-grid {
                grid-template-columns: 1fr;
            }

            .usuarios-grid {
                grid-template-columns: 1fr;
            }

            .filtros-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .usuario-stats {
                flex-direction: column;
                gap: 0.5rem;
            }

            .stat-item {
                text-align: left;
            }

            /* Hacer las columnas de estadísticas más pequeñas en móvil */
            .col-md-3 {
                margin-bottom: 0.5rem;
            }

            /* Hacer acordeones más compactos en móvil */
            .accordion-item {
                margin-bottom: 0.75rem;
            }

            .accordion-body {
                padding: 0.75rem;
            }

            /* Tablas más compactas en móvil */
            .table-sm td,
            .table-sm th {
                padding: 0.5rem 0.25rem;
                font-size: 0.8rem;
            }

            /* Botones más pequeños en móvil */
            .btn-sm {
                padding: 0.2rem 0.4rem;
                font-size: 0.75rem;
            }

            .action-btn {
                padding: 0.2rem 0.4rem;
                font-size: 0.7rem;
            }
        }

        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .book-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
            border: none;
        }

        .book-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .book-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #ffc107 0%, #e83e8c 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        /* Estilos para modo oscuro */
        .dark-theme {
            background-color: #1a1a1a !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .sidebar {
            background: #2d2d2d !important;
        }

        .dark-theme .content {
            background-color: #1a1a1a !important;
        }

        .dark-theme .stats-card {
            background-color: #2d2d2d !important;
            border: 1px solid #444 !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .stats-card:hover {
            background-color: #333 !important;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.3);
        }

        .dark-theme .section-title {
            color: #e0e0e0 !important;
        }

        .dark-theme .card {
            background-color: #2d2d2d !important;
            border: 1px solid #444 !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .card-header {
            background-color: #333 !important;
            color: #e0e0e0 !important;
            border-bottom: 1px solid #444 !important;
        }

        .dark-theme .table {
            background-color: #2d2d2d !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .table thead th {
            background-color: #333 !important;
            color: #e0e0e0 !important;
            border-color: #444 !important;
        }

        .dark-theme .table td, .dark-theme .table th {
            border-color: #444 !important;
        }

        .dark-theme .table-hover tbody tr:hover {
            background-color: rgba(255,255,255,0.075) !important;
        }

        .dark-theme .modal-content {
            background-color: #2d2d2d !important;
            color: #e0e0e0 !important;
            border: 1px solid #444 !important;
        }

        .dark-theme .modal-header {
            border-bottom: 1px solid #444 !important;
        }

        .dark-theme .modal-header .modal-title {
            color: #fff !important;
        }

        .dark-theme .modal-header.bg-warning .modal-title {
            color: #fff !important;
        }

        .dark-theme .modal-header.bg-danger .modal-title {
            color: #fff !important;
        }

        .dark-theme .modal-header.bg-primary .modal-title {
            color: #fff !important;
        }

        .dark-theme .modal-footer {
            border-top: 1px solid #444 !important;
        }

        .dark-theme .form-control {
            background-color: #333 !important;
            border-color: #555 !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .form-control:focus {
            background-color: #333 !important;
            border-color: #0d6efd !important;
            color: #e0e0e0 !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
        }

        .dark-theme .form-select {
            background-color: #333 !important;
            border-color: #555 !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .form-control::placeholder {
            color: #adb5bd !important;
            opacity: 1;
        }

        .dark-theme .input-group-text {
            background-color: #333 !important;
            border-color: #555 !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .btn-outline-secondary {
            color: #e0e0e0 !important;
            border-color: #555 !important;
        }

        .dark-theme .btn-outline-secondary:hover {
            background-color: #555 !important;
            border-color: #666 !important;
            color: #fff !important;
        }

        .dark-theme .alert-info {
            background-color: #0c4128 !important;
            border-color: #0f5132 !important;
            color: #a3cfbb !important;
        }

        .dark-theme .alert-warning {
            background-color: #664d03 !important;
            border-color: #997404 !important;
            color: #ffda6a !important;
        }

        .dark-theme .alert-danger {
            background-color: #58151c !important;
            border-color: #842029 !important;
            color: #ea868f !important;
        }

        .dark-theme .nav-tabs .nav-link {
            background-color: #333 !important;
            border-color: #444 !important;
            color: #e0e0e0 !important;
        }

        .dark-theme .nav-tabs .nav-link.active {
            background-color: #2d2d2d !important;
            border-color: #444 #444 #2d2d2d !important;
        }

        /* Estilos adicionales para modales en modo oscuro - Solo cambiar fondo, mantener texto negro */
        .dark-theme .modal-content {
            background-color: #2d2d2d !important;
            border: 1px solid #444 !important;
        }

        .dark-theme .modal-body {
            background-color: #2d2d2d !important;
        }

        .dark-theme .modal-footer {
            border-top: 1px solid #444 !important;
        }

        .dark-theme .modal-header {
            border-bottom: 1px solid #444 !important;
        }

        /* Solo los títulos de modal deben ser blancos */
        .dark-theme .modal-header .modal-title {
            color: #fff !important;
        }

        .dark-theme .modal-header.bg-warning .modal-title {
            color: #fff !important;
        }

        .dark-theme .modal-header.bg-danger .modal-title {
            color: #fff !important;
        }

        .dark-theme .modal-header.bg-primary .modal-title {
            color: #fff !important;
        }

        /* Cambiar solo fondo de formularios pero mantener texto negro */
        .dark-theme .form-control {
            background-color: #333 !important;
            border-color: #555 !important;
            color: #fff !important;
        }

        .dark-theme .form-control:focus {
            background-color: #333 !important;
            border-color: #0d6efd !important;
            color: #fff !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
        }

        .dark-theme .form-select {
            background-color: #333 !important;
            border-color: #555 !important;
            color: #fff !important;
        }

        .dark-theme .form-control::placeholder {
            color: #adb5bd !important;
            opacity: 1;
        }

        .dark-theme .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* Estilos específicos para elementos de modales en modo oscuro - Solo alertas cambian color */
        .dark-theme .modal-content .alert {
            border-color: #555 !important;
        }

        .dark-theme .modal-content .alert-warning {
            background-color: #664d03 !important;
            border-color: #997404 !important;
            color: #ffda6a !important;
        }

        .dark-theme .modal-content .alert-warning strong {
            color: #ffda6a !important;
        }

        .dark-theme .modal-content .alert-info {
            background-color: #0c4128 !important;
            border-color: #0f5132 !important;
            color: #a3cfbb !important;
        }

        .dark-theme .modal-content .alert-info strong {
            color: #a3cfbb !important;
        }

        .dark-theme .modal-content .alert-danger {
            background-color: #58151c !important;
            border-color: #842029 !important;
            color: #ea868f !important;
        }

        .dark-theme .modal-content .alert-danger strong {
            color: #ea868f !important;
        }

        /* Solo cambiar badges a blanco */
        .dark-theme .modal-content .badge {
            color: #fff !important;
        }

        /* Forzar color blanco en títulos de modales independientemente del fondo */
        .dark-theme .modal-header .modal-title,
        .dark-theme .modal-header.bg-danger .modal-title,
        .dark-theme .modal-header.bg-warning .modal-title,
        .dark-theme .modal-header.bg-primary .modal-title,
        .dark-theme .modal-header.bg-info .modal-title {
            color: #fff !important;
        }

        /* Animación para el botón de tema */
        .theme-toggle {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .theme-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .theme-icon {
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .theme-toggle:hover .theme-icon {
            transform: rotate(180deg);
        }

        /* Estilos para modales en modo claro */
        body:not(.dark-theme) .modal-content {
            background-color: #fff !important;
            color: #212529 !important;
        }

        body:not(.dark-theme) .modal-body {
            background-color: #fff !important;
            color: #212529 !important;
        }

        body:not(.dark-theme) .modal-title {
            color: #fff !important; /* Mantener blanco en header azul */
        }

        body:not(.dark-theme) .modal-content .text-muted {
            color: #6c757d !important;
        }

        body:not(.dark-theme) .modal-content label {
            color: #212529 !important;
        }

        body:not(.dark-theme) .modal-content .form-text {
            color: #6c757d !important;
        }

        body:not(.dark-theme) .modal-content strong {
            color: #212529 !important;
        }

        body:not(.dark-theme) .modal-content p,
        body:not(.dark-theme) .modal-content div,
        body:not(.dark-theme) .modal-content span:not(.badge) {
            color: #212529 !important;
        }

        /* Asegurar que en modo claro el contenido del modal sea legible */
        body:not(.dark-theme) .modal-content * {
            color: #212529 !important;
        }

        body:not(.dark-theme) .modal-content .text-muted {
            color: #6c757d !important;
        }

        body:not(.dark-theme) .modal-content .badge {
            color: #fff !important;
        }

        body:not(.dark-theme) .modal-header {
            color: #fff !important; /* Header siempre blanco */
        }

        body:not(.dark-theme) .modal-title {
            color: #fff !important; /* Título siempre blanco en header azul */
        }

        /* Estilos específicos para tablas dentro del modal en modo claro */
        body:not(.dark-theme) .modal-content .table {
            background-color: #fff !important;
            color: #212529 !important;
        }

        body:not(.dark-theme) .modal-content .table td,
        body:not(.dark-theme) .modal-content .table th {
            color: #212529 !important;
            border-color: #dee2e6 !important;
        }

        body:not(.dark-theme) .modal-content .table thead th {
            background-color: #343a40 !important;
            color: #fff !important;
        }

        body:not(.dark-theme) .modal-content .alert {
            color: inherit !important;
        }

        /* Navegación de tabs en modo claro */
        body:not(.dark-theme) .modal-content .nav-tabs .nav-link {
            background-color: #f8f9fa !important;
            border-color: #dee2e6 !important;
            color: #495057 !important;
        }

        body:not(.dark-theme) .modal-content .nav-tabs .nav-link.active {
            background-color: #fff !important;
            border-color: #dee2e6 #dee2e6 #fff !important;
            color: #495057 !important;
        }
    </style>
</head>
<body>
    <!-- Header móvil -->
    <div class="mobile-header d-md-none">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/LocalB/logo.jpg') }}" alt="Logo" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 10px;">
            <span class="fw-bold">Panel Bibliotecario</span>
        </div>
        <button class="mobile-menu-btn" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <!-- Overlay para cerrar sidebar en móvil -->
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <div class="container-fluid">
        <div class="row flex-nowrap">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-12 col-md-3 col-lg-2 sidebar d-flex flex-column p-0">
                <div class="sidebar-header">
                    <img src="{{ asset('img/LocalB/logo.jpg') }}" alt="Logo Biblioteca">
                    <h5 class="fw-bold mb-0">Bibliotecario</h5>
                    <small>Panel de gestión</small>
                </div>
                <ul class="nav flex-column px-2">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-prestamos" href="#" onclick="showTab('prestamos')">
                            <i class="bi bi-book"></i> Préstamos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-devoluciones" href="#" onclick="showTab('devoluciones')">
                            <i class="bi bi-arrow-repeat"></i> Devoluciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-sanciones" href="#" onclick="showTab('sanciones')">
                            <i class="bi bi-exclamation-triangle"></i> Sanciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-libros" href="#" onclick="showTab('libros')">
                            <i class="bi bi-journal"></i> Libros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-historial" href="#" onclick="showTab('historial')">
                            <i class="bi bi-clock-history"></i> Historial
                        </a>
                    </li>

                    <!-- Botón de modo claro/oscuro -->
                    <li class="nav-item mt-3">
                        <button class="btn btn-outline-light w-100 theme-toggle" onclick="toggleTheme()">
                            <i class="bi bi-sun-fill theme-icon"></i>
                            <span class="theme-text">Modo Oscuro</span>
                        </button>
                    </li>

                    <li class="nav-item mt-4">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-danger w-100"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</button>
                        </form>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col px-0 content">

                <!-- Sección de Préstamos -->
                <div id="panel-prestamos">
                    <div class="section-title">
                        <i class="bi bi-book"></i>
                        <h2>Gestión de Préstamos</h2>
                    </div>

                    <!-- Estadísticas de Préstamos -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="stats-icon prestamos">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <h4 class="fw-bold text-warning mb-1">{{ count($prestamos->where('estado', 'pendiente')) }}</h4>
                                <p class="text-muted mb-0 small">Préstamos Pendientes</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="stats-icon" style="background: #28a745;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <h4 class="fw-bold text-success mb-1">{{ count($prestamos->where('estado', 'activo')) }}</h4>
                                <p class="text-muted mb-0 small">Préstamos Activos</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="stats-icon" style="background: #dc3545;">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <h4 class="fw-bold text-danger mb-1">{{ count($prestamos->where('estado', 'denegado')) }}</h4>
                                <p class="text-muted mb-0 small">Préstamos Denegados</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="stats-icon" style="background: #6f42c1;">
                                    <i class="bi bi-archive"></i>
                                </div>
                                <h4 class="fw-bold text-info mb-1">{{ count($prestamos->where('estado', 'entregado')) }}</h4>
                                <p class="text-muted mb-0 small">Préstamos Completados</p>
                            </div>
                        </div>
                    </div>

                    <!-- Búsqueda por usuario -->
                    <div class="search-box mb-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="buscarUsuarioPrestamos" class="form-control"
                                   placeholder="Buscar por nombre o email del usuario..."
                                   onkeyup="filtrarUsuariosPrestamos()">
                        </div>
                    </div>

                    <!-- Acordeón de usuarios con préstamos pendientes -->
                    <div class="accordion" id="acordeonPrestamos">
                        @php
                            $usuariosConPrestamos = $prestamos->where('estado', 'pendiente')->groupBy('id_usuario');
                        @endphp

                        @forelse($usuariosConPrestamos as $idUsuario => $prestamosPorUsuario)
                            @php
                                $usuario = $prestamosPorUsuario->first()->usuario;
                                $nombreCompleto = ($usuario->nombre ?? '') . ' ' . ($usuario->apellido ?? '');
                                $email = $usuario->email ?? 'N/A';
                                $accordionId = 'prestamos-usuario-' . $idUsuario;
                            @endphp

                            <div class="accordion-item mb-3 usuario-prestamo-item"
                                 data-usuario-nombre="{{ strtolower($nombreCompleto) }}"
                                 data-usuario-email="{{ strtolower($email) }}">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#{{ $accordionId }}"
                                            aria-expanded="false" aria-controls="{{ $accordionId }}">
                                        <div class="d-flex align-items-center w-100">
                                            <div class="avatar-circle me-3">
                                                {{ strtoupper(substr($email, 0, 1)) }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong>{{ $nombreCompleto }}</strong>
                                                <br><small class="text-muted">{{ $email }}</small>
                                            </div>
                                            <span class="badge bg-warning text-dark me-2">
                                                {{ count($prestamosPorUsuario) }} solicitud(es) pendiente(s)
                                            </span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="{{ $accordionId }}" class="accordion-collapse collapse"
                                     data-bs-parent="#acordeonPrestamos">
                                    <div class="accordion-body">
                                        <!-- Botones de acción masiva por usuario -->
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-success btn-sm"
                                                        onclick="confirmarTodosPrestamosPorUsuario({{ $idUsuario }})"
                                                        title="Aprobar todos los préstamos de este usuario">
                                                    <i class="bi bi-check-all"></i> Aprobar Todo
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="denegarTodosPrestamosPorUsuario({{ $idUsuario }})"
                                                        title="Denegar todos los préstamos de este usuario">
                                                    <i class="bi bi-x-lg"></i> Denegar Todo
                                                </button>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Libro</th>
                                                        <th>F. Solicitud</th>
                                                        <th>F. Devolución</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($prestamosPorUsuario as $prestamo)
                                                        <tr>
                                                            <td>
                                                                <strong class="text-primary">{{ $prestamo->libro->titulo ?? 'N/A' }}</strong>
                                                                @if($prestamo->libro)
                                                                    <br><small class="text-muted">Código: {{ $prestamo->libro->codigo }}</small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-light text-dark">
                                                                    {{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d/m/Y') }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-primary">
                                                                    {{ \Carbon\Carbon::parse($prestamo->fecha_devolucion)->format('d/m/Y') }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group" role="group">
                                                                    <button type="button" class="btn action-btn btn-success btn-sm btn-confirmar-ajax"
                                                                            data-prestamo-id="{{ $prestamo->id_prestamo }}"
                                                                            data-usuario-id="{{ $idUsuario }}"
                                                                            title="Aprobar préstamo">
                                                                        <i class="bi bi-check"></i>
                                                                    </button>
                                                                    <button type="button" class="btn action-btn btn-danger btn-sm btn-denegar-ajax"
                                                                            data-prestamo-id="{{ $prestamo->id_prestamo }}"
                                                                            data-usuario-id="{{ $idUsuario }}"
                                                                            data-libro-titulo="{{ $prestamo->libro->titulo ?? 'N/A' }}"
                                                                            data-usuario-email="{{ $usuario->email ?? 'N/A' }}"
                                                                            title="Denegar préstamo">
                                                                        <i class="bi bi-x"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h5>No hay préstamos pendientes</h5>
                                <p class="text-muted">Todas las solicitudes han sido procesadas</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Sección de Devoluciones -->
                <div id="panel-devoluciones" style="display:none;">
                    <div class="section-title">
                        <i class="bi bi-arrow-repeat"></i>
                        <h2>Gestión de Devoluciones</h2>
                    </div>

                    <!-- Estadísticas de Devoluciones -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="stats-icon devoluciones">
                                    <i class="bi bi-book-half"></i>
                                </div>
                                @php
                                    $prestamosActivos = $prestamos->where('estado', 'activo');
                                @endphp
                                <h4 class="fw-bold text-info mb-1">{{ count($prestamosActivos) }}</h4>
                                <p class="text-muted mb-0 small">Libros Prestados</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="stats-icon" style="background: #dc3545;">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                @php
                                    $prestamosAtrasados = $prestamosActivos->filter(function($p) {
                                        return \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($p->fecha_devolucion));
                                    });
                                @endphp
                                <h4 class="fw-bold text-danger mb-1">{{ count($prestamosAtrasados) }}</h4>
                                <p class="text-muted mb-0 small">Fuera de Plazo</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="stats-icon" style="background: #28a745;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                @php
                                    $prestamosEnPlazo = $prestamosActivos->filter(function($p) {
                                        return \Carbon\Carbon::now()->lte(\Carbon\Carbon::parse($p->fecha_devolucion));
                                    });
                                @endphp
                                <h4 class="fw-bold text-success mb-1">{{ count($prestamosEnPlazo) }}</h4>
                                <p class="text-muted mb-0 small">En Plazo</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="stats-icon" style="background: #6f42c1;">
                                    <i class="bi bi-archive-fill"></i>
                                </div>
                                <h4 class="fw-bold text-secondary mb-1">{{ count($prestamos->where('estado', 'entregado')) }}</h4>
                                <p class="text-muted mb-0 small">Entregados Hoy</p>
                            </div>
                        </div>
                    </div>

                    <!-- Búsqueda por usuario -->
                    <div class="search-box mb-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="buscarUsuarioDevoluciones" class="form-control"
                                   placeholder="Buscar por nombre o email del usuario..."
                                   onkeyup="filtrarUsuariosDevoluciones()">
                        </div>
                    </div>

                    <!-- Acordeón de usuarios con devoluciones pendientes -->
                    <div class="accordion" id="acordeonDevoluciones">
                        @php
                            $usuariosConDevoluciones = $prestamos->where('estado', 'activo')->groupBy('id_usuario');
                        @endphp

                        @forelse($usuariosConDevoluciones as $idUsuario => $devolucionesPorUsuario)
                            @php
                                $usuario = $devolucionesPorUsuario->first()->usuario;
                                $nombreCompleto = ($usuario->nombre ?? '') . ' ' . ($usuario->apellido ?? '');
                                $email = $usuario->email ?? 'N/A';
                                $accordionId = 'devoluciones-usuario-' . $idUsuario;

                                // Calcular préstamos atrasados para este usuario
                                $now = \Carbon\Carbon::now();
                                $prestamosAtrasados = $devolucionesPorUsuario->filter(function($p) use ($now) {
                                    return $now->gt(\Carbon\Carbon::parse($p->fecha_devolucion));
                                });
                                $prestamosEnPlazo = $devolucionesPorUsuario->filter(function($p) use ($now) {
                                    return $now->lte(\Carbon\Carbon::parse($p->fecha_devolucion));
                                });
                            @endphp

                            <div class="accordion-item mb-3 usuario-devolucion-item"
                                 data-usuario-nombre="{{ strtolower($nombreCompleto) }}"
                                 data-usuario-email="{{ strtolower($email) }}">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#{{ $accordionId }}"
                                            aria-expanded="false" aria-controls="{{ $accordionId }}">
                                        <div class="d-flex align-items-center w-100">
                                            <div class="avatar-circle me-3">
                                                {{ strtoupper(substr($email, 0, 1)) }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong>{{ $nombreCompleto }}</strong>
                                                <br><small class="text-muted">{{ $email }}</small>
                                            </div>
                                            <div class="d-flex gap-2 me-2">
                                                @if(count($prestamosAtrasados) > 0)
                                                    <span class="badge bg-danger">
                                                        {{ count($prestamosAtrasados) }} atrasado(s)
                                                    </span>
                                                @endif
                                                @if(count($prestamosEnPlazo) > 0)
                                                    <span class="badge bg-success">
                                                        {{ count($prestamosEnPlazo) }} en plazo
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="{{ $accordionId }}" class="accordion-collapse collapse"
                                     data-bs-parent="#acordeonDevoluciones">
                                    <div class="accordion-body">
                                        <!-- Botones de acción masiva por usuario -->
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-success btn-sm"
                                                        onclick="registrarTodasDevolucionesPorUsuario({{ $idUsuario }})"
                                                        title="Registrar todas las devoluciones de este usuario">
                                                    <i class="bi bi-check-all"></i> Registrar Todo
                                                </button>
                                                @if(count($prestamosAtrasados) > 0)
                                                <button type="button" class="btn btn-warning btn-sm"
                                                        onclick="aplicarSancionMasiva({{ $idUsuario }})"
                                                        title="Aplicar sanción por todos los retrasos">
                                                    <i class="bi bi-exclamation-triangle"></i> Sancionar
                                                </button>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Libro</th>
                                                        <th>F. Préstamo</th>
                                                        <th>F. Límite</th>
                                                        <th>Estado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($devolucionesPorUsuario as $prestamo)
                                                        @php
                                                            $fechaDevolucion = \Carbon\Carbon::parse($prestamo->fecha_devolucion);
                                                            $ahora = \Carbon\Carbon::now();
                                                            $enPlazo = $ahora->lte($fechaDevolucion);
                                                            // Calcular días enteros
                                                            if ($enPlazo) {
                                                                $diasRestantes = $ahora->startOfDay()->diffInDays($fechaDevolucion->startOfDay());
                                                            } else {
                                                                $diasRestantes = $fechaDevolucion->startOfDay()->diffInDays($ahora->startOfDay());
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <strong class="text-primary">{{ $prestamo->libro->titulo ?? 'N/A' }}</strong>
                                                                @if($prestamo->libro)
                                                                    <br><small class="text-muted">Código: {{ $prestamo->libro->codigo }}</small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-light text-dark">
                                                                    {{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d/m/Y') }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="badge {{ $enPlazo ? 'bg-success' : 'bg-danger' }}">
                                                                    {{ $fechaDevolucion->format('d/m/Y') }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @if($enPlazo)
                                                                    @if($diasRestantes == 0)
                                                                        <span class="status-badge warning">
                                                                            <i class="bi bi-exclamation-circle me-1"></i>Vence hoy
                                                                        </span>
                                                                    @elseif($diasRestantes == 1)
                                                                        <span class="status-badge warning">
                                                                            <i class="bi bi-calendar-event me-1"></i>1d restante
                                                                        </span>
                                                                    @else
                                                                        <span class="status-badge success">
                                                                            <i class="bi bi-check-circle me-1"></i>{{ $diasRestantes }}d restantes
                                                                        </span>
                                                                    @endif
                                                                @else
                                                                    <span class="status-badge danger">
                                                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $diasRestantes }}d tarde
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="btn-group" role="group">
                                                                    <button type="button" class="btn action-btn btn-success btn-sm btn-devolucion-ajax"
                                                                            data-prestamo-id="{{ $prestamo->id_prestamo }}"
                                                                            data-usuario-id="{{ $idUsuario }}"
                                                                            title="Registrar devolución">
                                                                        <i class="bi bi-check-circle"></i>
                                                                    </button>
                                                                    @if(!$enPlazo)
                                                                        <button type="button" class="btn action-btn btn-warning btn-sm btn-sancion-retraso"
                                                                                title="Aplicar sanción por retraso"
                                                                                data-usuario-id="{{ $prestamo->usuario->id_usuario ?? 0 }}"
                                                                                data-usuario-nombre="{{ $prestamo->usuario->nombre ?? '' }} {{ $prestamo->usuario->apellido ?? '' }}"
                                                                                data-usuario-email="{{ $prestamo->usuario->email ?? '' }}"
                                                                                data-dias-retraso="{{ $diasRestantes }}">
                                                                            <i class="bi bi-exclamation-triangle"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h5>No hay devoluciones pendientes</h5>
                                <p class="text-muted">Todos los libros han sido devueltos</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Sanciones -->
                <div id="panel-sanciones" style="display:none;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold">Gestión de Sanciones</h2>
                    </div>

                    <!-- Filtro de búsqueda -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="buscarUsuario" placeholder="Buscar por nombre o email...">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="filtroSanciones">
                                <option value="todos">Todos los usuarios</option>
                                <option value="activas">Con sanciones activas</option>
                                <option value="sin_sanciones">Sin sanciones</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tabla de usuarios con historial de sanciones -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-people me-2"></i>Usuarios y Estado de Sanciones</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="tablaUsuarios">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre Completo</th>
                                            <th>Email</th>
                                            <th>Préstamos Activos</th>
                                            <th>Estado de Sanción</th>
                                            <th>Última Sanción</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($usuarios as $usuario)
                                        @php
                                            $prestamosActivos = \App\Models\Prestamo::where('id_usuario', $usuario->id_usuario)
                                                ->whereIn('estado', ['activo', 'pendiente'])->count();
                                            $prestamosAtrasados = \App\Models\Prestamo::where('id_usuario', $usuario->id_usuario)
                                                ->where('estado', 'activo')
                                                ->where('fecha_devolucion', '<', now())
                                                ->count();
                                            $sancionActiva = \App\Models\Sancion::where('id_usuario', $usuario->id_usuario)
                                                ->where('estado', 'activa')
                                                ->where('fecha_fin', '>', now())
                                                ->first();
                                            $ultimaSancion = \App\Models\Sancion::where('id_usuario', $usuario->id_usuario)
                                                ->latest('created_at')
                                                ->first();
                                        @endphp
                                        <tr class="usuario-row" data-nombre="{{ strtolower($usuario->nombre . ' ' . $usuario->apellido) }}" data-email="{{ strtolower($usuario->email) }}">
                                            <td><span class="badge bg-primary">{{ $usuario->id_usuario }}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-2">
                                                        {{ strtoupper(substr($usuario->nombre, 0, 1) . substr($usuario->apellido, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $usuario->nombre }} {{ $usuario->apellido }}</strong>
                                                        <br><small class="text-muted">Registro: {{ \Carbon\Carbon::parse($usuario->fecha_registro)->format('d/m/Y') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="bi bi-envelope text-muted me-1"></i>
                                                {{ $usuario->email }}
                                            </td>
                                            <td>
                                                @if($prestamosActivos > 0)
                                                    <div>
                                                        <span class="badge bg-info">{{ $prestamosActivos }} préstamo(s)</span>
                                                        @if($prestamosAtrasados > 0)
                                                            <br><span class="badge bg-danger mt-1">{{ $prestamosAtrasados }}/{{ $prestamosActivos }} atrasado(s)</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">Sin préstamos</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($sancionActiva)
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                                        Sancionado hasta {{ \Carbon\Carbon::parse($sancionActiva->fecha_fin)->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>
                                                        Sin sanciones
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($ultimaSancion)
                                                    <small class="text-muted">
                                                        {{ $ultimaSancion->tipo }}<br>
                                                        {{ \Carbon\Carbon::parse($ultimaSancion->created_at)->format('d/m/Y') }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">Ninguna</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-warning btn-sancion"
                                                            data-usuario-id="{{ $usuario->id_usuario }}"
                                                            data-usuario-nombre="{{ $usuario->nombre }} {{ $usuario->apellido }}"
                                                            data-usuario-email="{{ $usuario->email }}"
                                                            @if($sancionActiva) title="Usuario ya tiene sanción activa" disabled @endif
                                                            title="Aplicar sanción">
                                                        <i class="bi bi-exclamation-triangle"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-info btn-historial"
                                                            data-usuario-id="{{ $usuario->id_usuario }}"
                                                            data-usuario-nombre="{{ $usuario->nombre }} {{ $usuario->apellido }}"
                                                            title="Ver historial completo">
                                                        <i class="bi bi-clock-history"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Sanciones activas -->
                    @php
                        $sancionesActivas = \App\Models\Sancion::with('usuario')
                            ->where('estado', 'activa')
                            ->where('fecha_fin', '>', now())
                            ->get();
                    @endphp
                    @if($sancionesActivas->count() > 0)
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Sanciones Activas ({{ $sancionesActivas->count() }})</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Tipo de Sanción</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Días Restantes</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sancionesActivas as $sancion)
                                        @php
                                            $fechaFin = \Carbon\Carbon::parse($sancion->fecha_fin);
                                            $ahora = \Carbon\Carbon::now();
                                            $diasRestantes = $ahora->startOfDay()->diffInDays($fechaFin->startOfDay(), false);
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $sancion->usuario->nombre }} {{ $sancion->usuario->apellido }}</strong><br>
                                                <small class="text-muted">{{ $sancion->usuario->email }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark">{{ $sancion->tipo }}</span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($sancion->fecha_inicio)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($sancion->fecha_fin)->format('d/m/Y') }}</td>
                                            <td>
                                                @if($diasRestantes > 0)
                                                    <span class="badge bg-danger">{{ $diasRestantes }}d restantes</span>
                                                @else
                                                    <span class="badge bg-secondary">Vencida</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('bibliotecario.completarSancion') }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="id_sancion" value="{{ $sancion->id_sancion }}">
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Levantar esta sanción?')">
                                                        <i class="bi bi-check"></i> Levantar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Libros -->
                <div id="panel-libros" style="display:none;">
                    <!-- Título de la sección -->
                    <div class="section-title">
                        <i class="bi bi-journal"></i>
                        <div>
                            <span>Gestión de Inventario</span>
                            <p class="mb-0 fs-6 fw-normal opacity-75">Administra la disponibilidad de libros en la biblioteca</p>
                        </div>
                    </div>

                    <!-- Estadísticas rápidas -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="stats-icon" style="background: #6c757d;">
                                    <i class="bi bi-journal-bookmark"></i>
                                </div>
                                <h3 class="fw-bold text-warning">{{ count($libros) }}</h3>
                                <p class="text-muted mb-0">Total Libros</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="stats-icon" style="background: #28a745;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <h3 class="fw-bold text-success">{{ $libros->where('disponibles', '>', 0)->count() }}</h3>
                                <p class="text-muted mb-0">Disponibles</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="stats-icon" style="background: #dc3545;">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <h3 class="fw-bold text-danger">{{ $libros->where('disponibles', 0)->count() }}</h3>
                                <p class="text-muted mb-0">Agotados</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="stats-icon" style="background: #17a2b8;">
                                    <i class="bi bi-collection"></i>
                                </div>
                                <h3 class="fw-bold text-info">{{ $libros->sum('disponibles') }}</h3>
                                <p class="text-muted mb-0">Total Ejemplares</p>
                            </div>
                        </div>
                    </div>

                    <!-- Grid de libros -->
                    <div class="book-grid">
                        @foreach($libros as $libro)
                        <div class="book-card">
                            <div class="d-flex align-items-start">
                                <div class="book-icon me-3">
                                    <i class="bi bi-book"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold text-primary mb-1">{{ $libro->titulo }}</h5>
                                    <p class="text-muted mb-2">
                                        <small><strong>Código:</strong> {{ $libro->codigo }}</small>
                                    </p>

                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <span class="badge {{ $libro->disponibles > 0 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $libro->disponibles }} disponibles
                                                </span>
                                                <span class="badge bg-light text-dark ms-1">
                                                    {{ ucfirst($libro->estado) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <form method="POST" action="{{ route('bibliotecario.modificarDisponibilidad', ['codigo' => $libro->codigo]) }}" class="d-flex align-items-center">
                                                @csrf
                                                <input type="hidden" name="tab" value="libros">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" name="disponibilidad"
                                                           class="form-control"
                                                           value="{{ $libro->disponibles }}"
                                                           min="0" max="999" required
                                                           style="max-width: 80px;">
                                                    <button type="submit" class="btn action-btn btn-primary" title="Actualizar disponibilidad">
                                                        <i class="bi bi-check"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if(count($libros) == 0)
                    <div class="empty-state">
                        <i class="bi bi-book"></i>
                        <h5>No hay libros registrados</h5>
                        <p class="text-muted">Agrega libros al catálogo para comenzar</p>
                    </div>
                    @endif
                </div>

                <!-- Historial de Devoluciones -->
                <div id="panel-historial" style="display:none;">
                    <!-- Título de la sección -->
                    <div class="section-title">
                        <i class="bi bi-clock-history"></i>
                        <div>
                            <span>Historial de Devoluciones</span>
                        </div>
                    </div>

                    <!-- Estadísticas rápidas -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="stats-card">
                                <div class="stats-icon" style="background: #6c757d;">
                                    <i class="bi bi-collection"></i>
                                </div>
                                <h4 class="fw-bold text-secondary mb-1">{{ count($devoluciones) }}</h4>
                                <p class="text-muted mb-0 small">Total Devoluciones</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card">
                                <div class="stats-icon" style="background: #28a745;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <h4 class="fw-bold text-success mb-1">{{ $devoluciones->where('estado_libro', 'A tiempo')->count() }}</h4>
                                <p class="text-muted mb-0 small">A Tiempo</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card">
                                <div class="stats-icon" style="background: #dc3545;">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <h4 class="fw-bold text-danger mb-1">{{ $devoluciones->where('estado_libro', 'Tarde')->count() }}</h4>
                                <p class="text-muted mb-0 small">Con Retraso</p>
                            </div>
                        </div>
                    </div>

                    <!-- Búsqueda mejorada -->
                    <div class="search-box">
                        <form method="GET" action="{{ route('bibliotecario.dashboard') }}">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="buscar_historial" class="form-control"
                                       placeholder="Buscar por email del usuario..."
                                       value="{{ request('buscar_historial') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search me-1"></i>Buscar
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tabla moderna -->
                    <div class="table-responsive">
                        <table class="table table-modern table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-person me-1"></i>Usuario</th>
                                    <th><i class="bi bi-book me-1"></i>Libro</th>
                                    <th><i class="bi bi-calendar-event me-1"></i>F. Devolución</th>
                                    <th><i class="bi bi-flag me-1"></i>Estado Entrega</th>
                                    <th><i class="bi bi-chat-text me-1"></i>Observaciones</th>
                                </tr>
                            </thead>
                                <tbody>
                                    @forelse($devoluciones as $devolucion)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2">
                                                    {{ strtoupper(substr($devolucion->prestamo->usuario->email ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $devolucion->prestamo->usuario->email ?? 'N/A' }}</strong>
                                                    @if($devolucion->prestamo->usuario)
                                                        <br><small class="text-muted">{{ $devolucion->prestamo->usuario->nombre }} {{ $devolucion->prestamo->usuario->apellido }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ $devolucion->prestamo->libro->titulo ?? 'N/A' }}</strong>
                                            @if($devolucion->prestamo->libro)
                                                <br><small class="text-muted">Código: {{ $devolucion->prestamo->libro->codigo }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ \Carbon\Carbon::parse($devolucion->fecha_devolucion)->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($devolucion->estado_libro === 'A tiempo')
                                                <span class="status-badge success">
                                                    <i class="bi bi-check-circle me-1"></i>A tiempo
                                                </span>
                                            @else
                                                <span class="status-badge danger">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>Con retraso
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $devolucion->observaciones ?? 'Sin observaciones' }}
                                            </small>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <i class="bi bi-inbox"></i>
                                                <h5>No hay devoluciones registradas</h5>
                                                <p class="text-muted">El historial aparecerá cuando se registren devoluciones</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Modal para aplicar sanción -->
    <div class="modal fade" id="modalSancion" tabindex="-1" aria-labelledby="modalSancionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalSancionLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Aplicar Sanción a Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('bibliotecario.aplicarSancion') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id_usuario" id="usuario_id_sancion">

                        <div class="alert alert-info">
                            <strong>Usuario seleccionado:</strong>
                            <div id="info_usuario_sancion"></div>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_sancion" class="form-label">Tipo de Sanción</label>
                            <select class="form-select" name="tipo" id="tipo_sancion" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="retraso">Retraso en devolución</option>
                                <option value="daño">Daño al libro</option>
                                <option value="perdida">Pérdida del libro</option>
                                <option value="otro">Otro motivo</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="dias_bloqueo" class="form-label">Días de sanción</label>
                            <input type="number" class="form-control" name="dias_bloqueo" id="dias_bloqueo"
                                   min="1" max="365" required>
                            <div class="form-text">Número de días que durará la sanción (1-365 días)</div>
                        </div>

                        <div class="mb-3">
                            <label for="observaciones_sancion" class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones_sancion"
                                      rows="3" placeholder="Descripción detallada del motivo de la sanción..."></textarea>
                        </div>

                        <div class="alert alert-warning">
                            <small><strong>Nota:</strong> La sanción impedirá al usuario solicitar nuevos préstamos durante el período especificado.</small>
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

    <!-- Modal para historial completo del usuario -->
    <div class="modal fade" id="modalHistorialUsuario" tabindex="-1" aria-labelledby="modalHistorialUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalHistorialUsuarioLabel">
                        <i class="bi bi-person-lines-fill me-2"></i>Historial Completo del Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="info_usuario_historial" class="alert alert-info mb-4">
                        <!-- Información del usuario se carga dinámicamente -->
                    </div>

                    <!-- Tabs del historial -->
                    <ul class="nav nav-tabs" id="historialTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="sanciones-tab" data-bs-toggle="tab" data-bs-target="#sanciones-content" type="button" role="tab">
                                <i class="bi bi-exclamation-triangle me-1"></i>Sanciones
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="prestamos-tab" data-bs-toggle="tab" data-bs-target="#prestamos-content" type="button" role="tab">
                                <i class="bi bi-book me-1"></i>Préstamos
                            </button>
                        </li>
                    </ul>

                    <!-- Contenido de los tabs -->
                    <div class="tab-content mt-3" id="historialTabsContent">
                        <!-- Tab de Sanciones -->
                        <div class="tab-pane fade show active" id="sanciones-content" role="tabpanel">
                            <div id="contenido_sanciones_usuario">
                                <!-- Contenido se carga dinámicamente -->
                            </div>
                        </div>

                        <!-- Tab de Préstamos -->
                        <div class="tab-pane fade" id="prestamos-content" role="tabpanel">
                            <div id="contenido_prestamos_usuario">
                                <!-- Contenido se carga dinámicamente -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para denegar préstamo -->
    <div class="modal fade" id="modalDenegarPrestamo" tabindex="-1" aria-labelledby="modalDenegarPrestamoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalDenegarPrestamoLabel">
                        <i class="bi bi-x-circle me-2"></i>Denegar Préstamo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="" id="formDenegarPrestamo">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="tab" value="prestamos">

                        <div class="alert alert-warning">
                            <strong>¿Está seguro de que desea denegar este préstamo?</strong>
                            <div id="info_prestamo_denegar" class="mt-2">
                                <!-- Se llena dinámicamente -->
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="observaciones_denegar" class="form-label">Motivo de la denegación</label>
                            <textarea class="form-control" name="observaciones" id="observaciones_denegar"
                                      rows="3" placeholder="Ingrese el motivo por el cual se deniega el préstamo..." required></textarea>
                            <div class="form-text">Este motivo será visible para el usuario solicitante.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle me-1"></i>Denegar Préstamo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para mostrar tabs
        function showTab(tab) {
            // Ocultar todos los paneles
            document.getElementById('panel-prestamos').style.display = tab === 'prestamos' ? '' : 'none';
            document.getElementById('panel-devoluciones').style.display = tab === 'devoluciones' ? '' : 'none';
            document.getElementById('panel-sanciones').style.display = tab === 'sanciones' ? '' : 'none';
            document.getElementById('panel-libros').style.display = tab === 'libros' ? '' : 'none';
            document.getElementById('panel-historial').style.display = tab === 'historial' ? '' : 'none';

            // Actualizar clases activas en el menú
            document.getElementById('tab-prestamos').classList.toggle('active', tab === 'prestamos');
            document.getElementById('tab-devoluciones').classList.toggle('active', tab === 'devoluciones');
            document.getElementById('tab-sanciones').classList.toggle('active', tab === 'sanciones');
            document.getElementById('tab-libros').classList.toggle('active', tab === 'libros');
            document.getElementById('tab-historial').classList.toggle('active', tab === 'historial');

            // Actualiza el parámetro tab en la URL sin recargar
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.replaceState({}, '', url);

            // Scroll automático al panel activo
            const panelId = 'panel-' + tab;
            const panel = document.getElementById(panelId);
            if (panel) {
                setTimeout(() => {
                    panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        }

        // Funciones para la nueva sección de solicitudes
        function buscarUsuariosEnTiempoReal() {
            const buscar = document.getElementById('buscarUsuarioSolicitudes').value;

            if (buscar.length >= 2 || buscar.length === 0) {
                const url = new URL(window.location);
                url.searchParams.set('buscar', buscar);
                window.location.href = url.toString();
            }
        }

        // Función para filtrar por tipo de solicitud (solo frontend)
        function filtrarPorTipo() {
            const filtro = document.getElementById('filtroTipoSolicitud').value;
            const usuarios = document.querySelectorAll('#accordionSolicitudes .accordion-item');

            usuarios.forEach(usuario => {
                const tipoSolicitudes = usuario.getAttribute('data-tipo-solicitudes') || '';
                const esAtrasado = usuario.getAttribute('data-atrasado') === 'true';

                let mostrar = false;

                switch(filtro) {
                    case 'todos':
                        mostrar = true;
                        break;
                    case 'prestamos':
                        mostrar = tipoSolicitudes.includes('prestamos');
                        break;
                    case 'devoluciones':
                        mostrar = tipoSolicitudes.includes('devoluciones');
                        break;
                    case 'atrasados':
                        mostrar = esAtrasado;
                        break;
                }

                usuario.style.display = mostrar ? 'block' : 'none';
            });
        }

        // Búsqueda en tiempo real por texto
        document.addEventListener('DOMContentLoaded', function() {
            const inputBuscar = document.getElementById('buscarUsuarioSolicitudes');
            if (inputBuscar) {
                inputBuscar.addEventListener('input', function() {
                    const texto = this.value.toLowerCase();
                    const usuarios = document.querySelectorAll('#accordionSolicitudes .accordion-item');

                    usuarios.forEach(usuario => {
                        const datosUsuario = usuario.getAttribute('data-usuario-datos') || '';
                        const coincide = datosUsuario.includes(texto);

                        // Solo aplicar filtro de texto si no hay filtro de tipo activo
                        const filtroTipo = document.getElementById('filtroTipoSolicitud').value;
                        if (filtroTipo === 'todos') {
                            usuario.style.display = coincide ? 'block' : 'none';
                        } else {
                            // Combinar ambos filtros
                            const tipoSolicitudes = usuario.getAttribute('data-tipo-solicitudes') || '';
                            const esAtrasado = usuario.getAttribute('data-atrasado') === 'true';

                            let mostrarPorTipo = false;
                            switch(filtroTipo) {
                                case 'prestamos':
                                    mostrarPorTipo = tipoSolicitudes.includes('prestamos');
                                    break;
                                case 'devoluciones':
                                    mostrarPorTipo = tipoSolicitudes.includes('devoluciones');
                                    break;
                                case 'atrasados':
                                    mostrarPorTipo = esAtrasado;
                                    break;
                            }

                            usuario.style.display = (coincide && mostrarPorTipo) ? 'block' : 'none';
                        }
                    });
                });
            }

            // Detectar parámetro tab en URL al cargar
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab') || 'prestamos';
            showTab(tab);
        });

        // Función para abrir modal de sanción
        function abrirModalSancion(idUsuario, nombreCompleto, email) {
            document.getElementById('usuario_id_sancion').value = idUsuario;
            document.getElementById('info_usuario_sancion').innerHTML =
                `<strong>${nombreCompleto}</strong><br><small class="text-muted">${email}</small>`;

            // Limpiar formulario
            document.getElementById('tipo_sancion').value = '';
            document.getElementById('dias_bloqueo').value = '';
            document.getElementById('observaciones_sancion').value = '';

            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('modalSancion'));
            modal.show();
        }

        // Función para abrir modal de sanción por retraso
        function abrirModalSancionPorRetraso(idUsuario, nombreCompleto, email, diasRetraso) {
            document.getElementById('usuario_id_sancion').value = idUsuario;
            document.getElementById('info_usuario_sancion').innerHTML =
                `<strong>${nombreCompleto}</strong><br><small class="text-muted">${email}</small>`;

            // Pre-llenar formulario con datos de retraso
            document.getElementById('tipo_sancion').value = 'retraso';
            document.getElementById('dias_bloqueo').value = Math.min(7 + diasRetraso, 30); // 7 días base + días de retraso, máximo 30
            document.getElementById('observaciones_sancion').value = `Sanción por retraso de ${diasRetraso} días en la devolución de libro`;

            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('modalSancion'));
            modal.show();
        }

        // Función para ver historial completo del usuario
        function verHistorialUsuario(idUsuario, nombreCompleto) {
            document.getElementById('info_usuario_historial').innerHTML =
                `<strong>Usuario:</strong> ${nombreCompleto}<br><strong>ID:</strong> ${idUsuario}`;

            // Cargar sanciones del usuario
            cargarSancionesUsuario(idUsuario);

            // Cargar préstamos del usuario
            cargarPrestamosUsuario(idUsuario);

            const modal = new bootstrap.Modal(document.getElementById('modalHistorialUsuario'));
            modal.show();
        }

        // Función para manejar el modal de denegación de préstamo
        document.addEventListener('DOMContentLoaded', function() {
            const modalDenegar = document.getElementById('modalDenegarPrestamo');
            if (modalDenegar) {
                modalDenegar.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const prestamoId = button.getAttribute('data-prestamo-id');
                    const libroTitulo = button.getAttribute('data-libro-titulo');
                    const usuarioEmail = button.getAttribute('data-usuario-email');

                    // Actualizar la información del préstamo en el modal
                    document.getElementById('info_prestamo_denegar').innerHTML =
                        `<strong>Libro:</strong> ${libroTitulo}<br><strong>Usuario:</strong> ${usuarioEmail}`;

                    // Actualizar la acción del formulario
                    document.getElementById('formDenegarPrestamo').action =
                        `/bibliotecario/prestamo/${prestamoId}/denegar`;

                    // Limpiar el textarea de observaciones
                    document.getElementById('observaciones_denegar').value = '';
                });
            }
        });

        // Función para cargar sanciones del usuario
        function cargarSancionesUsuario(idUsuario) {
            fetch(`/bibliotecario/usuario/${idUsuario}/sanciones`)
                .then(response => response.json())
                .then(sanciones => {
                    let html = '';
                    if (sanciones.length === 0) {
                        html = '<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Este usuario no tiene sanciones registradas.</div>';
                    } else {
                        html = `
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Días</th>
                                            <th>Estado</th>
                                            <th>Observaciones</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;

                        sanciones.forEach(sancion => {
                            const esActiva = sancion.estado === 'activa' && new Date(sancion.fecha_fin) > new Date();
                            html += `
                                <tr>
                                    <td><span class="badge bg-warning text-dark">${sancion.tipo}</span></td>
                                    <td>${formatDate(sancion.fecha_inicio)}</td>
                                    <td>${formatDate(sancion.fecha_fin)}</td>
                                    <td>${sancion.dias_bloqueo}</td>
                                    <td>
                                        ${esActiva ?
                                            '<span class="badge bg-danger">Activa</span>' :
                                            '<span class="badge bg-success">Completada</span>'
                                        }
                                    </td>
                                    <td>${sancion.observaciones || '-'}</td>
                                    <td>
                                        ${esActiva ?
                                            `<button class="btn btn-sm btn-success" onclick="levantarSancion(${sancion.id_sancion})" title="Levantar sanción">
                                                <i class="bi bi-check"></i>
                                            </button>` :
                                            `<button class="btn btn-sm btn-danger" onclick="eliminarSancion(${sancion.id_sancion})" title="Eliminar del historial">
                                                <i class="bi bi-trash"></i>
                                            </button>`
                                        }
                                    </td>
                                </tr>
                            `;
                        });

                        html += '</tbody></table></div>';
                    }
                    document.getElementById('contenido_sanciones_usuario').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('contenido_sanciones_usuario').innerHTML =
                        '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>Error al cargar las sanciones.</div>';
                });
        }

        // Función para cargar préstamos del usuario
        function cargarPrestamosUsuario(idUsuario) {
            fetch(`/bibliotecario/usuario/${idUsuario}/prestamos`)
                .then(response => response.json())
                .then(prestamos => {
                    let html = '';
                    if (prestamos.length === 0) {
                        html = '<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Este usuario no tiene préstamos registrados.</div>';
                    } else {
                        html = `
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Libro</th>
                                            <th>Fecha Préstamo</th>
                                            <th>Fecha Devolución</th>
                                            <th>Fecha Real</th>
                                            <th>Estado</th>
                                            <th>Días Atraso</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;

                        prestamos.forEach(prestamo => {
                            const fechaDevolucion = new Date(prestamo.fecha_devolucion);
                            const fechaReal = prestamo.fecha_entrega_real ? new Date(prestamo.fecha_entrega_real) : null;

                            let diasAtraso = 0;

                            // Calcular días de atraso según el estado
                            if (prestamo.estado === 'activo') {
                                // Préstamo activo: comparar fecha actual con fecha límite
                                const ahora = new Date();
                                if (ahora > fechaDevolucion) {
                                    diasAtraso = Math.ceil((ahora - fechaDevolucion) / (1000 * 60 * 60 * 24));
                                }
                            } else if (prestamo.estado === 'devuelto' && fechaReal) {
                                // Préstamo devuelto: comparar fecha real de entrega con fecha límite
                                if (fechaReal > fechaDevolucion) {
                                    diasAtraso = Math.ceil((fechaReal - fechaDevolucion) / (1000 * 60 * 60 * 24));
                                }
                            }

                            html += `
                                <tr>
                                    <td><strong>${prestamo.libro?.titulo || 'N/A'}</strong></td>
                                    <td>${formatDate(prestamo.fecha_prestamo)}</td>
                                    <td>${formatDate(prestamo.fecha_devolucion)}</td>
                                    <td>${fechaReal ? formatDate(prestamo.fecha_entrega_real) : '-'}</td>
                                    <td>
                                        ${getEstadoBadge(prestamo.estado)}
                                    </td>
                                    <td>
                                        ${diasAtraso > 0 ?
                                            `<span class="badge bg-danger">${diasAtraso}d atraso</span>` :
                                            '<span class="text-muted">-</span>'
                                        }
                                    </td>
                                </tr>
                            `;
                        });

                        html += '</tbody></table></div>';
                    }
                    document.getElementById('contenido_prestamos_usuario').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('contenido_prestamos_usuario').innerHTML =
                        '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>Error al cargar los préstamos.</div>';
                });
        }

        // Función para levantar sanción
        function levantarSancion(idSancion) {
            if (confirm('¿Está seguro de que desea levantar esta sanción?')) {
                fetch('/bibliotecario/sancion/completar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ id_sancion: idSancion })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error al levantar la sanción');
                    }
                });
            }
        }

        // Función para eliminar sanción del historial
        function eliminarSancion(idSancion) {
            if (confirm('¿Está seguro de que desea eliminar esta sanción del historial? Esta acción no se puede deshacer.')) {
                fetch('/bibliotecario/sancion/eliminar', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ id_sancion: idSancion })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error al eliminar la sanción');
                    }
                });
            }
        }

        // Función para alternar modo claro/oscuro
        function toggleTheme() {
            const body = document.body;
            const isDark = body.classList.contains('dark-theme');

            if (isDark) {
                body.classList.remove('dark-theme');
                localStorage.setItem('theme', 'light');
                updateThemeButton('light');
            } else {
                body.classList.add('dark-theme');
                localStorage.setItem('theme', 'dark');
                updateThemeButton('dark');
            }
        }

        // Función para actualizar el botón de tema
        function updateThemeButton(theme) {
            const themeIcon = document.querySelector('.theme-icon');
            const themeText = document.querySelector('.theme-text');

            if (theme === 'dark') {
                themeIcon.className = 'bi bi-moon-fill theme-icon';
                themeText.textContent = 'Modo Claro';
            } else {
                themeIcon.className = 'bi bi-sun-fill theme-icon';
                themeText.textContent = 'Modo Oscuro';
            }
        }

        // Funciones auxiliares
        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('es-ES');
        }

        function getEstadoBadge(estado) {
            const badges = {
                'pendiente': '<span class="badge bg-warning text-dark">Pendiente</span>',
                'activo': '<span class="badge bg-info">Activo</span>',
                'entregado': '<span class="badge bg-success">Entregado</span>',
                'devuelto': '<span class="badge bg-success">Devuelto</span>',
                'denegado': '<span class="badge bg-danger">Denegado</span>',
                'retraso': '<span class="badge bg-danger">En retraso</span>'
            };
            return badges[estado] || '<span class="badge bg-secondary">Desconocido</span>';
        }

        // Función de búsqueda de usuarios en préstamos
        function filtrarUsuariosPrestamos() {
            const busqueda = document.getElementById('buscarUsuarioPrestamos').value.toLowerCase();
            const items = document.querySelectorAll('.usuario-prestamo-item');

            items.forEach(item => {
                const nombre = item.dataset.usuarioNombre || '';
                const email = item.dataset.usuarioEmail || '';

                if (nombre.includes(busqueda) || email.includes(busqueda)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Función de búsqueda de usuarios en devoluciones
        function filtrarUsuariosDevoluciones() {
            const busqueda = document.getElementById('buscarUsuarioDevoluciones').value.toLowerCase();
            const items = document.querySelectorAll('.usuario-devolucion-item');

            items.forEach(item => {
                const nombre = item.dataset.usuarioNombre || '';
                const email = item.dataset.usuarioEmail || '';

                if (nombre.includes(busqueda) || email.includes(busqueda)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Función de búsqueda de usuarios
        function filtrarUsuarios() {
            const busqueda = document.getElementById('buscarUsuario').value.toLowerCase();
            const filtro = document.getElementById('filtroSanciones').value;
            const filas = document.querySelectorAll('#tablaUsuarios tbody tr.usuario-row');

            filas.forEach(fila => {
                const nombre = fila.dataset.nombre || '';
                const email = fila.dataset.email || '';
                const sancionBadge = fila.querySelector('td:nth-child(5) .badge');

                // Filtro de búsqueda por texto
                const coincideTexto = nombre.includes(busqueda) || email.includes(busqueda);

                // Filtro por estado de sanción
                let coincideFiltro = true;
                if (filtro === 'activas') {
                    coincideFiltro = sancionBadge && sancionBadge.classList.contains('bg-danger');
                } else if (filtro === 'sin_sanciones') {
                    coincideFiltro = sancionBadge && sancionBadge.classList.contains('bg-success');
                }

                // Mostrar/ocultar fila
                if (coincideTexto && coincideFiltro) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        }

        // Configuración de tipos de sanción predefinidos
        document.getElementById('tipo_sancion').addEventListener('change', function() {
            const diasInput = document.getElementById('dias_bloqueo');
            const observacionesTextarea = document.getElementById('observaciones_sancion');

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

        // ============ FUNCIONES AJAX ============

        // Función para confirmar préstamo individual vía AJAX
        function confirmarPrestamoAjax(prestamoId, usuarioId) {
            const button = document.querySelector(`[data-prestamo-id="${prestamoId}"].btn-confirmar-ajax`);
            const originalContent = button.innerHTML;

            button.disabled = true;
            button.innerHTML = '<i class="bi bi-hourglass-split"></i>';

            fetch(`/bibliotecario/ajax/prestamo/${prestamoId}/confirmar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tab: 'prestamos' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remover la fila del préstamo confirmado
                    const row = button.closest('tr');
                    row.style.transition = 'opacity 0.3s ease';
                    row.style.opacity = '0';

                    setTimeout(() => {
                        row.remove();

                        // Actualizar badge del acordeón y verificar si está vacío
                        actualizarBadgeAcordeon(usuarioId, 'prestamos');

                        // Actualizar estadísticas generales
                        actualizarEstadisticasPrestamos();

                        // Mostrar mensaje de éxito
                        mostrarNotificacion('Préstamo confirmado correctamente', 'success');
                    }, 300);
                } else {
                    button.disabled = false;
                    button.innerHTML = originalContent;
                    mostrarNotificacion(data.message || 'Error al confirmar préstamo', 'error');
                }
            })
            .catch(error => {
                button.disabled = false;
                button.innerHTML = originalContent;
                mostrarNotificacion('Error de conexión', 'error');
                console.error('Error:', error);
            });
        }

        // Función para denegar préstamo individual vía AJAX
        function denegarPrestamoAjax(prestamoId, usuarioId, observaciones) {
            return fetch(`/bibliotecario/ajax/prestamo/${prestamoId}/denegar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    observaciones: observaciones,
                    tab: 'prestamos'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remover la fila del préstamo denegado
                    const button = document.querySelector(`[data-prestamo-id="${prestamoId}"].btn-denegar-ajax`);
                    const row = button.closest('tr');
                    row.style.transition = 'opacity 0.3s ease';
                    row.style.opacity = '0';

                    setTimeout(() => {
                        row.remove();

                        // Actualizar badge del acordeón y verificar si está vacío
                        actualizarBadgeAcordeon(usuarioId, 'prestamos');

                        // Actualizar estadísticas generales
                        actualizarEstadisticasPrestamos();

                        // Mostrar mensaje de éxito
                        mostrarNotificacion('Préstamo denegado correctamente', 'warning');
                    }, 300);
                } else {
                    mostrarNotificacion(data.message || 'Error al denegar préstamo', 'error');
                }
                return data;
            });
        }

        // Función para registrar devolución individual vía AJAX
        function registrarDevolucionAjax(prestamoId, usuarioId) {
            const button = document.querySelector(`[data-prestamo-id="${prestamoId}"].btn-devolucion-ajax`);
            const originalContent = button.innerHTML;

            button.disabled = true;
            button.innerHTML = '<i class="bi bi-hourglass-split"></i>';

            fetch('/bibliotecario/ajax/devolucion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id_prestamo: prestamoId,
                    tab: 'devoluciones'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remover la fila de la devolución registrada
                    const row = button.closest('tr');
                    row.style.transition = 'opacity 0.3s ease';
                    row.style.opacity = '0';

                    setTimeout(() => {
                        row.remove();

                        // Actualizar badge del acordeón y verificar si está vacío
                        actualizarBadgeAcordeon(usuarioId, 'devoluciones');

                        // Actualizar estadísticas generales
                        actualizarEstadisticasDevoluciones();

                        // Mostrar mensaje de éxito
                        mostrarNotificacion('Devolución registrada correctamente', 'success');
                    }, 300);
                } else {
                    button.disabled = false;
                    button.innerHTML = originalContent;
                    mostrarNotificacion(data.message || 'Error al registrar devolución', 'error');
                }
            })
            .catch(error => {
                button.disabled = false;
                button.innerHTML = originalContent;
                mostrarNotificacion('Error de conexión', 'error');
                console.error('Error:', error);
            });
        }

        // Función para confirmar todos los préstamos de un usuario
        function confirmarTodosPrestamosPorUsuario(usuarioId) {
            if (!confirm('¿Confirmar TODOS los préstamos de este usuario?')) return;

            const buttons = document.querySelectorAll(`[data-usuario-id="${usuarioId}"].btn-confirmar-ajax`);

            buttons.forEach(button => {
                const prestamoId = button.getAttribute('data-prestamo-id');
                confirmarPrestamoAjax(prestamoId, usuarioId);
            });
        }

        // Función para denegar todos los préstamos de un usuario
        function denegarTodosPrestamosPorUsuario(usuarioId) {
            const observaciones = prompt('Motivo para denegar todos los préstamos:');
            if (!observaciones) return;

            const buttons = document.querySelectorAll(`[data-usuario-id="${usuarioId}"].btn-denegar-ajax`);

            let promises = [];
            buttons.forEach(button => {
                const prestamoId = button.getAttribute('data-prestamo-id');
                promises.push(denegarPrestamoAjax(prestamoId, usuarioId, observaciones));
            });

            Promise.all(promises).then(() => {
                mostrarNotificacion('Todos los préstamos han sido denegados', 'warning');
            });
        }

        // Función para registrar todas las devoluciones de un usuario
        function registrarTodasDevolucionesPorUsuario(usuarioId) {
            if (!confirm('¿Registrar TODAS las devoluciones de este usuario?')) return;

            const buttons = document.querySelectorAll(`[data-usuario-id="${usuarioId}"].btn-devolucion-ajax`);

            buttons.forEach(button => {
                const prestamoId = button.getAttribute('data-prestamo-id');
                registrarDevolucionAjax(prestamoId, usuarioId);
            });
        }

        // Función para aplicar sanción masiva por retrasos
        function aplicarSancionMasiva(usuarioId) {
            // Obtener información del usuario desde el acordeón
            const accordionItem = document.querySelector(`[data-usuario-id="${usuarioId}"]`).closest('.accordion-item');
            const userInfo = accordionItem.querySelector('.accordion-button');
            const userName = userInfo.querySelector('strong').textContent;
            const userEmail = userInfo.querySelector('small').textContent;

            // Contar total de días de retraso
            const retrasoButtons = accordionItem.querySelectorAll('.btn-sancion-retraso');
            let totalDiasRetraso = 0;
            retrasoButtons.forEach(button => {
                totalDiasRetraso += parseInt(button.getAttribute('data-dias-retraso')) || 0;
            });

            if (confirm(`¿Aplicar sanción por retraso acumulado de ${totalDiasRetraso} días a ${userName}?`)) {
                abrirModalSancionPorRetraso(usuarioId, userName, userEmail, totalDiasRetraso);
            }
        }

        // Función para actualizar estadísticas de préstamos
        function actualizarEstadisticasPrestamos() {
            fetch('/bibliotecario/ajax/estadisticas?tab=prestamos', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar estadísticas generales
                    actualizarEstadisticasGenerales(data.estadisticas, 'prestamos');

                    // Verificar si hay acordeones vacíos para remover
                    verificarAcordeonesVacios('prestamos');
                }
            })
            .catch(error => {
                console.error('Error al actualizar estadísticas de préstamos:', error);
            });
        }

        // Función para actualizar estadísticas de devoluciones
        function actualizarEstadisticasDevoluciones() {
            fetch('/bibliotecario/ajax/estadisticas?tab=devoluciones', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar estadísticas generales
                    actualizarEstadisticasGenerales(data.estadisticas, 'devoluciones');

                    // Verificar si hay acordeones vacíos para remover
                    verificarAcordeonesVacios('devoluciones');
                }
            })
            .catch(error => {
                console.error('Error al actualizar estadísticas de devoluciones:', error);
            });
        }

        // Función para actualizar estadísticas generales en el dashboard
        function actualizarEstadisticasGenerales(estadisticas, tab) {
            if (tab === 'prestamos') {
                // Actualizar contadores de préstamos
                const statsCards = document.querySelectorAll('#panel-prestamos .stats-card h4');
                if (statsCards.length >= 4) {
                    statsCards[0].textContent = estadisticas.pendientes || 0; // Pendientes
                    statsCards[1].textContent = estadisticas.activos || 0;    // Activos
                    statsCards[2].textContent = estadisticas.denegados || 0;  // Denegados
                    statsCards[3].textContent = estadisticas.entregados || 0; // Completados
                }
            } else if (tab === 'devoluciones') {
                // Para devoluciones, no hay estadísticas específicas que actualizar en las cards
                // pero podríamos agregar contadores si fuera necesario
            }
        }

        // Función para verificar y remover acordeones vacíos
        function verificarAcordeonesVacios(tab) {
            const acordeonSelector = tab === 'prestamos' ? '#acordeonPrestamos' : '#acordeonDevoluciones';
            const acordeon = document.querySelector(acordeonSelector);

            if (acordeon) {
                const items = acordeon.querySelectorAll('.accordion-item');
                items.forEach(item => {
                    const accordionBody = item.querySelector('.accordion-body');
                    const rows = accordionBody.querySelectorAll('tbody tr');

                    if (rows.length === 0) {
                        // Si no hay filas, remover el acordeón completo
                        item.style.transition = 'opacity 0.3s ease';
                        item.style.opacity = '0';
                        setTimeout(() => {
                            if (item.parentNode) {
                                item.remove();

                                // Verificar si el acordeón completo está vacío
                                const remainingItems = acordeon.querySelectorAll('.accordion-item');
                                if (remainingItems.length === 0) {
                                    // Mostrar mensaje de estado vacío
                                    mostrarEstadoVacio(tab);
                                }
                            }
                        }, 300);
                    }
                });
            }
        }

        // Función para mostrar estado vacío cuando no hay más elementos
        function mostrarEstadoVacio(tab) {
            const panelSelector = tab === 'prestamos' ? '#panel-prestamos' : '#panel-devoluciones';
            const acordeonSelector = tab === 'prestamos' ? '#acordeonPrestamos' : '#acordeonDevoluciones';
            const panel = document.querySelector(panelSelector);
            const acordeon = document.querySelector(acordeonSelector);

            if (panel && acordeon) {
                const mensaje = tab === 'prestamos'
                    ? 'No hay solicitudes de préstamos pendientes'
                    : 'No hay devoluciones pendientes';
                const descripcion = tab === 'prestamos'
                    ? 'Todas las solicitudes han sido procesadas'
                    : 'Todos los libros han sido devueltos';

                const estadoVacio = document.createElement('div');
                estadoVacio.className = 'empty-state';
                estadoVacio.innerHTML = `
                    <i class="bi bi-inbox"></i>
                    <h5>${mensaje}</h5>
                    <p class="text-muted">${descripcion}</p>
                `;

                // Reemplazar el acordeón con el estado vacío
                acordeon.parentNode.replaceChild(estadoVacio, acordeon);
            }
        }

        // Función para actualizar el badge de un acordeón específico
        function actualizarBadgeAcordeon(usuarioId, tab) {
            const acordeonSelector = tab === 'prestamos'
                ? `#prestamos-usuario-${usuarioId}`
                : `#devoluciones-usuario-${usuarioId}`;

            const accordionItem = document.querySelector(`[data-bs-target="${acordeonSelector}"]`)?.closest('.accordion-item');

            if (accordionItem) {
                const accordionBody = accordionItem.querySelector('.accordion-body');
                const rows = accordionBody?.querySelectorAll('tbody tr') || [];

                if (rows.length === 0) {
                    // Si no hay más filas, remover el acordeón
                    accordionItem.style.transition = 'opacity 0.3s ease';
                    accordionItem.style.opacity = '0';
                    setTimeout(() => {
                        if (accordionItem.parentNode) {
                            accordionItem.remove();
                            verificarAcordeonesVacios(tab);
                        }
                    }, 300);
                } else {
                    // Actualizar el badge con el nuevo conteo
                    const button = accordionItem.querySelector('.accordion-button');
                    const badge = button?.querySelector('.badge');

                    if (badge && tab === 'prestamos') {
                        badge.textContent = `${rows.length} solicitud(es) pendiente(s)`;
                    } else if (tab === 'devoluciones') {
                        // Para devoluciones, necesitamos recalcular los badges de "en plazo" y "atrasados"
                        actualizarBadgesDevoluciones(accordionItem, rows);
                    }
                }
            }
        }

        // Función para actualizar badges específicos de devoluciones (en plazo/atrasados)
        function actualizarBadgesDevoluciones(accordionItem, rows) {
            const badgeContainer = accordionItem.querySelector('.d-flex.gap-2.me-2');
            if (!badgeContainer) return;

            let atrasados = 0;
            let enPlazo = 0;

            rows.forEach(row => {
                const estadoBadge = row.querySelector('.badge');
                if (estadoBadge) {
                    if (estadoBadge.classList.contains('bg-danger')) {
                        atrasados++;
                    } else if (estadoBadge.classList.contains('bg-success')) {
                        enPlazo++;
                    }
                }
            });

            // Limpiar badges existentes
            badgeContainer.innerHTML = '';

            // Agregar badges actualizados
            if (atrasados > 0) {
                const badgeAtrasados = document.createElement('span');
                badgeAtrasados.className = 'badge bg-danger';
                badgeAtrasados.textContent = `${atrasados} atrasado(s)`;
                badgeContainer.appendChild(badgeAtrasados);
            }

            if (enPlazo > 0) {
                const badgeEnPlazo = document.createElement('span');
                badgeEnPlazo.className = 'badge bg-success';
                badgeEnPlazo.textContent = `${enPlazo} en plazo`;
                badgeContainer.appendChild(badgeEnPlazo);
            }
        }

        // ============ FIN FUNCIONES AJAX ============

        // Eventos de DOM cargado
        document.addEventListener('DOMContentLoaded', function () {
            // Configurar tab inicial
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab') || 'prestamos';
            showTab(tab);

            // Configurar eventos de búsqueda
            document.getElementById('buscarUsuario')?.addEventListener('input', filtrarUsuarios);
            document.getElementById('filtroSanciones')?.addEventListener('change', filtrarUsuarios);

            // Event listeners para botones de sanción
            document.querySelectorAll('.btn-sancion').forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.disabled) {
                        const usuarioId = this.getAttribute('data-usuario-id');
                        const usuarioNombre = this.getAttribute('data-usuario-nombre');
                        const usuarioEmail = this.getAttribute('data-usuario-email');
                        abrirModalSancion(usuarioId, usuarioNombre, usuarioEmail);
                    }
                });
            });

            // Event listeners para botones de historial
            document.querySelectorAll('.btn-historial').forEach(button => {
                button.addEventListener('click', function() {
                    const usuarioId = this.getAttribute('data-usuario-id');
                    const usuarioNombre = this.getAttribute('data-usuario-nombre');
                    verHistorialUsuario(usuarioId, usuarioNombre);
                });
            });

            // Event listeners para botones de sanción por retraso
            document.querySelectorAll('.btn-sancion-retraso').forEach(button => {
                button.addEventListener('click', function() {
                    const usuarioId = this.getAttribute('data-usuario-id');
                    const usuarioNombre = this.getAttribute('data-usuario-nombre');
                    const usuarioEmail = this.getAttribute('data-usuario-email');
                    const diasRetraso = this.getAttribute('data-dias-retraso');
                    abrirModalSancionPorRetraso(usuarioId, usuarioNombre, usuarioEmail, diasRetraso);
                });
            });

            // Event listeners para botones AJAX de préstamos
            document.querySelectorAll('.btn-confirmar-ajax').forEach(button => {
                button.addEventListener('click', function() {
                    const prestamoId = this.getAttribute('data-prestamo-id');
                    const usuarioId = this.getAttribute('data-usuario-id');
                    confirmarPrestamoAjax(prestamoId, usuarioId);
                });
            });

            document.querySelectorAll('.btn-denegar-ajax').forEach(button => {
                button.addEventListener('click', function() {
                    const prestamoId = this.getAttribute('data-prestamo-id');
                    const usuarioId = this.getAttribute('data-usuario-id');
                    const libroTitulo = this.getAttribute('data-libro-titulo');
                    const usuarioEmail = this.getAttribute('data-usuario-email');

                    const observaciones = prompt(`Motivo para denegar el préstamo del libro "${libroTitulo}" a ${usuarioEmail}:`);
                    if (observaciones) {
                        denegarPrestamoAjax(prestamoId, usuarioId, observaciones);
                    }
                });
            });

            // Event listeners para botones AJAX de devoluciones
            document.querySelectorAll('.btn-devolucion-ajax').forEach(button => {
                button.addEventListener('click', function() {
                    const prestamoId = this.getAttribute('data-prestamo-id');
                    const usuarioId = this.getAttribute('data-usuario-id');
                    registrarDevolucionAjax(prestamoId, usuarioId);
                });
            });

            // Inicializar tema
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-theme');
            }
            updateThemeButton(savedTheme);
        });

        // Funciones para el sidebar móvil
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }

        // Cerrar sidebar al hacer clic en enlaces de navegación en móvil
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 767) {
                        closeSidebar();
                    }
                });
            });
        });
    </script>
</body>
</html>
