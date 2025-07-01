<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistema Biblioteca - Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0B1C2B 0%, #1e3556 50%, #2c5282 100%);
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Animación de partículas flotantes */
        .floating-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Contenedor principal */
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            padding: 2rem 1rem;
        }

        .register-content {
            max-width: 800px;
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        /* Header del formulario */
        .form-header {
            background: linear-gradient(135deg, #0B1C2B 0%, #1e3556 100%);
            color: white;
            padding: 3rem 2rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(11, 94, 215, 0.1) 0%, transparent 70%);
            animation: pulse 8s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.1); opacity: 0.6; }
        }

        .register-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0B5ED7 0%, #6f42c1 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(11, 94, 215, 0.3);
            position: relative;
            z-index: 2;
        }

        .header-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            z-index: 2;
        }

        .header-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        /* Panel del formulario */
        .form-panel {
            background: rgba(11, 28, 43, 0.95);
            padding: 3rem 2.5rem;
            backdrop-filter: blur(20px);
            color: #ffffff;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            backdrop-filter: blur(10px);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
            font-style: italic;
        }

        .form-control:focus {
            border-color: #00a8cc;
            box-shadow: 0 0 0 0.2rem rgba(0, 168, 204, 0.25);
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
            color: #ffffff;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            z-index: 3;
        }

        .form-control.with-icon {
            padding-left: 2.75rem;
        }

        .input-group-text {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-left: none;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            border-radius: 0 12px 12px 0;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .input-group:focus-within .input-group-text {
            border-color: #00a8cc;
            background: linear-gradient(135deg, rgba(0, 168, 204, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            color: #00a8cc;
        }

        .input-group .form-control {
            border-radius: 12px 0 0 12px;
        }

        .form-text {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 0.25rem;
            font-style: italic;
        }

        .btn-register {
            background: linear-gradient(135deg, #0B1C2B 0%, #1e3556 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(11, 28, 43, 0.3);
        }

        .btn-register:hover {
            background: linear-gradient(135deg, #1e3556 0%, #2c5282 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(11, 28, 43, 0.4);
            color: white;
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .login-link a {
            color: #00a8cc;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
            padding: 1rem 1.25rem;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(248, 215, 218, 0.2) 0%, rgba(245, 198, 203, 0.2) 100%);
            color: #ff6b7a;
            border-left: 4px solid #dc3545;
            backdrop-filter: blur(10px);
            border-radius: 12px;
        }

        .alert ul {
            margin: 0;
            padding-left: 1rem;
        }

        .progress-steps {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2rem;
            gap: 1rem;
        }

        .step {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #dee2e6;
            transition: all 0.3s ease;
        }

        .step.active {
            background: #0B5ED7;
            transform: scale(1.2);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .register-content {
                max-width: 700px;
            }

            .form-panel {
                padding: 2.5rem 2rem;
            }
        }

        @media (max-width: 768px) {
            .register-container {
                padding: 1rem;
                min-height: 100vh;
                align-items: flex-start;
                padding-top: 2rem;
            }

            .register-content {
                max-width: 100%;
                border-radius: 20px;
            }

            .form-header {
                padding: 2rem 1.5rem 1.5rem;
            }

            .form-panel {
                padding: 2rem 1.5rem;
            }

            .header-title {
                font-size: 1.75rem;
            }

            .register-icon {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }

            .row .col-md-6 {
                margin-bottom: 1rem;
            }

            .row .col-md-6:last-child {
                margin-bottom: 0;
            }
        }

        @media (max-width: 576px) {
            .register-container {
                padding: 0.5rem;
                padding-top: 1rem;
            }

            .form-header {
                padding: 1.5rem 1rem;
            }

            .form-panel {
                padding: 1.5rem 1rem;
            }

            .header-title {
                font-size: 1.5rem;
            }

            .header-subtitle {
                font-size: 0.9rem;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .form-label {
                font-size: 0.8rem;
                margin-bottom: 0.4rem;
            }

            .form-control {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .form-control.with-icon {
                padding-left: 2.5rem;
            }

            .input-icon {
                left: 0.75rem;
                font-size: 0.9rem;
            }

            .btn-register {
                padding: 0.875rem 1.5rem;
                font-size: 0.9rem;
                letter-spacing: 0.5px;
            }

            .alert {
                font-size: 0.85rem;
                padding: 0.75rem 1rem;
            }

            .login-link p {
                font-size: 0.85rem;
            }

            /* Ocultar partículas en móvil */
            .floating-particles {
                display: none;
            }
        }

        @media (max-width: 400px) {
            .register-container {
                padding: 0.25rem;
                padding-top: 0.5rem;
            }

            .form-header {
                padding: 1.25rem 0.75rem;
            }

            .form-panel {
                padding: 1.25rem 0.75rem;
            }

            .register-content {
                border-radius: 15px;
            }

            .header-title {
                font-size: 1.25rem;
            }

            .form-control {
                padding: 0.625rem 0.875rem;
                font-size: 0.85rem;
            }

            .form-control.with-icon {
                padding-left: 2.25rem;
            }

            .input-icon {
                left: 0.625rem;
                font-size: 0.8rem;
            }

            .btn-register {
                padding: 0.75rem 1.25rem;
                font-size: 0.85rem;
            }
        }

        /* Responsive para tablets en modo landscape */
        @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
            .register-container {
                padding: 1rem;
            }

            .register-content {
                max-width: 700px;
            }

            .form-header {
                padding: 2rem 1.5rem 1.5rem;
            }

            .form-panel {
                padding: 2rem 1.5rem;
            }

            .header-title {
                font-size: 1.75rem;
            }

            .register-icon {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
        }

        /* Ajustes para pantallas cortas */
        @media (max-height: 600px) {
            .register-container {
                align-items: flex-start;
                padding-top: 1rem;
            }

            .form-header {
                padding: 1.5rem 2rem 1rem;
            }

            .floating-particles {
                display: none;
            }
        }

        /* Modo oscuro */
        @media (prefers-color-scheme: dark) {
            .form-panel {
                background: rgba(11, 28, 43, 0.98);
                color: #ffffff;
            }

            .form-control {
                background: rgba(255, 255, 255, 0.1);
                border-color: rgba(255, 255, 255, 0.2);
                color: #ffffff;
            }

            .form-control::placeholder {
                color: rgba(255, 255, 255, 0.6);
            }

            .form-control:focus {
                background: rgba(255, 255, 255, 0.15);
                border-color: #00a8cc;
                color: #ffffff;
            }

            .input-group-text {
                background: rgba(255, 255, 255, 0.1);
                border-color: rgba(255, 255, 255, 0.2);
                color: rgba(255, 255, 255, 0.8);
            }

            .form-label {
                color: #ffffff;
            }

            .form-text {
                color: rgba(255, 255, 255, 0.7);
            }

            .input-icon {
                color: rgba(255, 255, 255, 0.7);
            }
        }
    </style>
</head>
<body>
    <!-- Partículas flotantes de fondo -->
    <div class="floating-particles">
        <div class="particle" style="left: 15%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 25%; animation-delay: 3s;"></div>
        <div class="particle" style="left: 35%; animation-delay: 6s;"></div>
        <div class="particle" style="left: 45%; animation-delay: 9s;"></div>
        <div class="particle" style="left: 55%; animation-delay: 12s;"></div>
        <div class="particle" style="left: 65%; animation-delay: 15s;"></div>
        <div class="particle" style="left: 75%; animation-delay: 18s;"></div>
        <div class="particle" style="left: 85%; animation-delay: 21s;"></div>
    </div>

    <!-- Contenedor principal del registro -->
    <div class="register-container">
        <div class="register-content">
            <!-- Header del formulario -->
            <div class="form-header">
                <div class="register-icon">
                    <i class="bi bi-person-plus"></i>
                </div>
                <h1 class="header-title">Crear Cuenta</h1>
                <p class="header-subtitle">Únete al Sistema de Biblioteca Digital</p>

                <!-- Indicador de progreso visual -->
                <div class="progress-steps">
                    <div class="step active"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                </div>
            </div>

            <!-- Panel del formulario -->
            <div class="form-panel">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul class="mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('registro.enviar') }}" id="registerForm">
                    @csrf

                    <!-- Información Personal -->
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="form-group">
                                <label for="nombre" class="form-label">
                                    <i class="bi bi-person me-1"></i>Nombre
                                </label>
                                <div class="input-group">
                                    <i class="bi bi-person input-icon"></i>
                                    <input type="text"
                                           class="form-control with-icon"
                                           id="nombre"
                                           name="nombre"
                                           value="{{ old('nombre') }}"
                                           placeholder="Tu nombre"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellido" class="form-label">
                                    <i class="bi bi-person me-1"></i>Apellido
                                </label>
                                <div class="input-group">
                                    <i class="bi bi-person input-icon"></i>
                                    <input type="text"
                                           class="form-control with-icon"
                                           id="apellido"
                                           name="apellido"
                                           value="{{ old('apellido') }}"
                                           placeholder="Tu apellido"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos de Identificación -->
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="form-group">
                                <label for="dni" class="form-label">
                                    <i class="bi bi-card-text me-1"></i>DNI
                                </label>
                                <div class="input-group">
                                    <i class="bi bi-card-text input-icon"></i>
                                    <input type="text"
                                           class="form-control with-icon"
                                           id="dni"
                                           name="dni"
                                           value="{{ old('dni') }}"
                                           pattern="[0-9]{8}"
                                           maxlength="8"
                                           placeholder="12345678"
                                           required>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>Documento de 8 dígitos
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codigo_estudiante" class="form-label">
                                    <i class="bi bi-mortarboard me-1"></i>Código de Estudiante
                                </label>
                                <div class="input-group">
                                    <i class="bi bi-mortarboard input-icon"></i>
                                    <input type="text"
                                           class="form-control with-icon"
                                           id="codigo_estudiante"
                                           name="codigo_estudiante"
                                           value="{{ old('codigo_estudiante') }}"
                                           placeholder="C20210001"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Institucional -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>Correo Electrónico Institucional
                        </label>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control"
                                   id="email_username"
                                   name="email_username"
                                   value="{{ old('email_username') }}"
                                   placeholder="tu.nombre"
                                   required>
                            <span class="input-group-text">@tecsup.edu.pe</span>
                        </div>
                        <input type="hidden" id="email" name="email" value="{{ old('email') }}">
                        <div class="form-text">
                            <i class="bi bi-shield-check me-1"></i>Utiliza tu correo institucional de Tecsup
                        </div>
                    </div>

                    <!-- Contraseñas -->
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-1"></i>Contraseña
                                </label>
                                <div class="input-group">
                                    <i class="bi bi-lock input-icon"></i>
                                    <input type="password"
                                           class="form-control with-icon"
                                           id="password"
                                           name="password"
                                           placeholder="••••••••"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>Confirmar Contraseña
                                </label>
                                <div class="input-group">
                                    <i class="bi bi-lock-fill input-icon"></i>
                                    <input type="password"
                                           class="form-control with-icon"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="••••••••"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-register">
                        <i class="bi bi-person-plus me-2"></i>
                        Crear Mi Cuenta
                    </button>
                </form>

                <div class="login-link">
                    <p class="mb-0">
                        ¿Ya tienes una cuenta?
                        <a href="{{ route('login') }}">Inicia sesión aquí</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ===== LÓGICA ORIGINAL MANTENIDA =====

        // Validación del DNI (solo números, 8 dígitos)
        document.getElementById('dni').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8);
        });

        // Combinar email antes de enviar el formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const emailUsername = document.getElementById('email_username').value;
            const fullEmail = emailUsername + '@tecsup.edu.pe';

            // Actualizar el campo oculto con el email completo
            document.getElementById('email').value = fullEmail;
        });

        // Validación en tiempo real del código de estudiante
        document.getElementById('codigo_estudiante').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // ===== MEJORAS UX Y RESPONSIVE =====

        document.addEventListener('DOMContentLoaded', function() {
            const isMobile = window.innerWidth <= 768;

            // Detectar si es dispositivo móvil
            function isMobileDevice() {
                return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            }

            // Ajustar viewport height en móviles
            function adjustViewportHeight() {
                if (isMobileDevice()) {
                    const vh = window.innerHeight * 0.01;
                    document.documentElement.style.setProperty('--vh', `${vh}px`);
                }
            }

            // Animación de entrada del formulario
            const formPanel = document.querySelector('.form-panel');
            const formHeader = document.querySelector('.form-header');

            setTimeout(() => {
                if (formHeader) {
                    formHeader.style.opacity = '0';
                    formHeader.style.transform = 'translateY(-20px)';
                    formHeader.style.transition = 'all 0.6s ease';

                    setTimeout(() => {
                        formHeader.style.opacity = '1';
                        formHeader.style.transform = 'translateY(0)';
                    }, 100);
                }

                if (formPanel) {
                    formPanel.style.opacity = '0';
                    formPanel.style.transform = isMobile ? 'translateY(20px)' : 'translateY(30px)';
                    formPanel.style.transition = 'all 0.6s ease';

                    setTimeout(() => {
                        formPanel.style.opacity = '1';
                        formPanel.style.transform = 'translateY(0)';
                    }, 200);
                }
            }, 100);

            // Efectos de focus en inputs (más suaves en móvil)
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    if (!isMobile) {
                        this.parentElement.style.transform = 'translateY(-2px)';
                    }
                    this.parentElement.style.transition = 'transform 0.2s ease';

                    // Actualizar progreso visual
                    updateProgressSteps();
                });

                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });

                // Prevenir zoom en iOS
                if (isMobileDevice() && /iPad|iPhone|iPod/.test(navigator.userAgent)) {
                    input.addEventListener('focus', function() {
                        this.style.fontSize = '16px';
                    });
                }
            });

            // Actualizar indicador de progreso
            function updateProgressSteps() {
                const steps = document.querySelectorAll('.step');
                const requiredInputs = document.querySelectorAll('input[required]');
                let filledInputs = 0;

                requiredInputs.forEach(input => {
                    if (input.value.trim() !== '') {
                        filledInputs++;
                    }
                });

                const progressPercentage = (filledInputs / requiredInputs.length) * 100;

                steps.forEach((step, index) => {
                    if (index < Math.ceil((filledInputs / requiredInputs.length) * 3)) {
                        step.classList.add('active');
                    } else {
                        step.classList.remove('active');
                    }
                });
            }

            // Escuchar cambios en todos los inputs para actualizar progreso
            inputs.forEach(input => {
                input.addEventListener('input', updateProgressSteps);
            });

            // Efecto de loading al enviar formulario
            const registerForm = document.getElementById('registerForm');
            const registerBtn = document.querySelector('.btn-register');

            registerForm.addEventListener('submit', function() {
                registerBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creando cuenta...';
                registerBtn.disabled = true;
                registerBtn.style.opacity = '0.7';
            });

            // Ajustar partículas según el dispositivo
            const particles = document.querySelectorAll('.particle');
            if (isMobileDevice() || window.innerWidth <= 768) {
                particles.forEach(particle => {
                    particle.style.display = 'none';
                });
            }

            // Validación visual en tiempo real
            function setupFieldValidation() {
                // DNI validation
                const dniInput = document.getElementById('dni');
                dniInput.addEventListener('input', function() {
                    if (this.value.length === 8) {
                        this.style.borderColor = '#28a745';
                    } else {
                        this.style.borderColor = '#e9ecef';
                    }
                });

                // Email validation
                const emailInput = document.getElementById('email_username');
                emailInput.addEventListener('input', function() {
                    const emailRegex = /^[a-zA-Z0-9._-]+$/;
                    if (emailRegex.test(this.value) && this.value.length > 2) {
                        this.style.borderColor = '#28a745';
                    } else {
                        this.style.borderColor = '#e9ecef';
                    }
                });

                // Password confirmation
                const passwordInput = document.getElementById('password');
                const confirmInput = document.getElementById('password_confirmation');

                function validatePasswords() {
                    if (passwordInput.value && confirmInput.value) {
                        if (passwordInput.value === confirmInput.value) {
                            confirmInput.style.borderColor = '#28a745';
                        } else {
                            confirmInput.style.borderColor = '#dc3545';
                        }
                    } else {
                        confirmInput.style.borderColor = '#e9ecef';
                    }
                }

                passwordInput.addEventListener('input', validatePasswords);
                confirmInput.addEventListener('input', validatePasswords);
            }

            setupFieldValidation();

            // Llamar a ajustar viewport
            adjustViewportHeight();

            // Reajustar en cambio de orientación
            window.addEventListener('resize', adjustViewportHeight);
            window.addEventListener('orientationchange', function() {
                setTimeout(adjustViewportHeight, 100);
            });

            // Mejorar accesibilidad en móvil
            if (isMobileDevice()) {
                // Agregar clase para móvil
                document.body.classList.add('mobile-device');

                // Ajustar scroll suave
                document.documentElement.style.scrollBehavior = 'smooth';

                // Prevenir doble tap zoom
                let lastTouchEnd = 0;
                document.addEventListener('touchend', function (event) {
                    const now = (new Date()).getTime();
                    if (now - lastTouchEnd <= 300) {
                        event.preventDefault();
                    }
                    lastTouchEnd = now;
                }, false);
            }

            // Optimizaciones para rendimiento en dispositivos lentos
            const isLowPerformance = /Android.*Chrome\/[1-9][0-9]/.test(navigator.userAgent) &&
                                    window.devicePixelRatio > 1;

            if (isLowPerformance || isMobile) {
                // Reducir animaciones en dispositivos lentos
                const style = document.createElement('style');
                style.textContent = `
                    .particle { animation-duration: 25s !important; }
                    .form-header::before { animation: none !important; }
                    * { transition-duration: 0.2s !important; }
                `;
                document.head.appendChild(style);
            }
        });

        // Agregar estilos CSS dinámicos para viewport en móvil
        const style = document.createElement('style');
        style.textContent = `
            @media (max-width: 768px) {
                .register-container {
                    min-height: calc(var(--vh, 1vh) * 100);
                }
            }

            .mobile-device .form-control:focus {
                transform: none !important;
            }

            .mobile-device .btn-register:hover {
                transform: none !important;
            }

            .mobile-device .particle {
                display: none !important;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
