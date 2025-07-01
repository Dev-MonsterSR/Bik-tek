
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Biblioteca - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float 15s infinite linear;
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
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            padding: 2rem 1rem;
        }

        .login-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 1200px;
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            min-height: 600px;
        }

        /* Panel izquierdo - Hero */
        .hero-panel {
            background: linear-gradient(135deg, #0B1C2B 0%, #1e3556 100%);
            color: white;
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-panel::before {
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

        .logo-container {
            position: relative;
            z-index: 2;
            margin-bottom: 2rem;
        }

        .logo-icon {
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
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .feature-list {
            list-style: none;
            text-align: left;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .feature-item i {
            color: #0B5ED7;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        /* Panel derecho - Formulario */
        .form-panel {
            background: rgba(11, 28, 43, 0.95);
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            backdrop-filter: blur(20px);
            color: #ffffff;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
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

        .btn-login {
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

        .btn-login:hover {
            background: linear-gradient(135deg, #1e3556 0%, #2c5282 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(11, 28, 43, 0.4);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .register-link a {
            color: #00a8cc;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        .alert {
            border-radius: 10px;
            border: none;
            margin-top: 1rem;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(248, 215, 218, 0.2) 0%, rgba(245, 198, 203, 0.2) 100%);
            color: #ff6b7a;
            border-left: 4px solid #dc3545;
            backdrop-filter: blur(10px);
            border-radius: 12px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .login-content {
                max-width: 900px;
            }

            .hero-panel {
                padding: 3rem 2rem;
            }

            .form-panel {
                padding: 3rem 2.5rem;
            }
        }

        @media (max-width: 992px) {
            .login-content {
                grid-template-columns: 1fr;
                max-width: 500px;
                min-height: auto;
            }

            .hero-panel {
                display: none;
            }

            .form-panel {
                padding: 3rem 2rem;
                border-radius: 25px;
                background: rgba(11, 28, 43, 0.95);
                backdrop-filter: blur(20px);
            }

            .form-title {
                font-size: 1.75rem;
                color: #ffffff;
            }

            .form-subtitle {
                color: rgba(255, 255, 255, 0.8);
            }
        }

        @media (max-width: 768px) {
            .login-container {
                padding: 1rem;
                min-height: 100vh;
                align-items: center;
                padding-top: 2rem;
                padding-bottom: 2rem;
            }

            .login-content {
                max-width: 100%;
                margin: 0;
                border-radius: 20px;
            }

            .form-panel {
                padding: 2.5rem 1.5rem;
                background: rgba(11, 28, 43, 0.95);
                backdrop-filter: blur(20px);
            }

            .form-title {
                font-size: 1.5rem;
                color: #ffffff;
            }

            .form-subtitle {
                font-size: 0.9rem;
                color: rgba(255, 255, 255, 0.8);
            }

            .form-label {
                color: #ffffff;
                font-size: 0.85rem;
            }

            .form-control {
                background: rgba(255, 255, 255, 0.1);
                border-color: rgba(255, 255, 255, 0.2);
                color: #ffffff;
                font-size: 0.9rem;
            }

            .form-control::placeholder {
                color: rgba(255, 255, 255, 0.6);
            }

            .input-icon {
                color: rgba(255, 255, 255, 0.7);
            }

            .register-link {
                color: rgba(255, 255, 255, 0.9);
            }

            .logo-icon {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }

            .hero-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 0.5rem;
                padding-top: 1rem;
                padding-bottom: 1rem;
                min-height: 100vh;
                align-items: center;
            }

            .form-panel {
                padding: 2rem 1rem;
                background: rgba(11, 28, 43, 0.95);
                backdrop-filter: blur(20px);
            }

            .form-header {
                margin-bottom: 2rem;
            }

            .form-title {
                font-size: 1.25rem;
                color: #ffffff;
            }

            .form-subtitle {
                font-size: 0.85rem;
                color: rgba(255, 255, 255, 0.8);
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .form-label {
                font-size: 0.8rem;
                margin-bottom: 0.4rem;
                color: #ffffff;
            }

            .form-control {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
                background: rgba(255, 255, 255, 0.1);
                border-color: rgba(255, 255, 255, 0.2);
                color: #ffffff;
            }

            .form-control::placeholder {
                color: rgba(255, 255, 255, 0.6);
            }

            .form-control.with-icon {
                padding-left: 2.5rem;
            }

            .input-icon {
                left: 0.75rem;
                font-size: 0.9rem;
                color: rgba(255, 255, 255, 0.7);
            }

            .btn-login {
                padding: 0.875rem 1.5rem;
                font-size: 0.9rem;
                letter-spacing: 0.5px;
            }

            .alert {
                font-size: 0.85rem;
                padding: 0.75rem;
            }

            .register-link {
                margin-top: 1rem;
                color: rgba(255, 255, 255, 0.9);
            }

            .register-link p {
                font-size: 0.85rem;
            }

            .register-link a {
                color: #00a8cc;
            }

            /* Ajustar partículas en móvil */
            .floating-particles {
                display: none;
            }
        }

        @media (max-width: 400px) {
            .login-container {
                padding: 0.25rem;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
                min-height: 100vh;
                align-items: center;
            }

            .form-panel {
                padding: 1.5rem 0.75rem;
                background: rgba(11, 28, 43, 0.95);
                backdrop-filter: blur(20px);
            }

            .login-content {
                border-radius: 15px;
            }

            .form-title {
                font-size: 1.1rem;
                color: #ffffff;
            }

            .form-subtitle {
                color: rgba(255, 255, 255, 0.8);
            }

            .form-label {
                color: #ffffff;
            }

            .form-control {
                padding: 0.625rem 0.875rem;
                font-size: 0.85rem;
                background: rgba(255, 255, 255, 0.1);
                border-color: rgba(255, 255, 255, 0.2);
                color: #ffffff;
            }

            .form-control::placeholder {
                color: rgba(255, 255, 255, 0.6);
            }

            .form-control.with-icon {
                padding-left: 2.25rem;
            }

            .input-icon {
                left: 0.625rem;
                font-size: 0.8rem;
                color: rgba(255, 255, 255, 0.7);
            }

            .btn-login {
                padding: 0.75rem 1.25rem;
                font-size: 0.85rem;
            }

            .register-link {
                color: rgba(255, 255, 255, 0.9);
            }

            .register-link a {
                color: #00a8cc;
            }
        }

        /* Responsive para tablets en modo landscape */
        @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
            .login-container {
                padding: 1rem;
            }

            .login-content {
                max-width: 800px;
                min-height: 500px;
            }

            .hero-panel {
                padding: 2rem 1.5rem;
            }

            .form-panel {
                padding: 2rem 1.5rem;
            }

            .hero-title {
                font-size: 2rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .logo-icon {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
        }

        /* Ajustes especiales para pantallas muy pequeñas */
        @media (max-height: 600px) {
            .login-container {
                align-items: flex-start;
                padding-top: 1rem;
            }

            .form-header {
                margin-bottom: 1.5rem;
            }

            .floating-particles {
                display: none;
            }
        }

        /* Ajustes para pantallas ultra anchas */
        @media (min-width: 1400px) {
            .login-content {
                max-width: 1300px;
            }

            .hero-panel {
                padding: 5rem 4rem;
            }

            .form-panel {
                padding: 5rem 4rem;
            }
        }

        /* Modo oscuro */
        @media (prefers-color-scheme: dark) {
            .form-panel {
                background: rgba(11, 28, 43, 0.98);
                color: #ffffff;
            }

            .form-title {
                color: #ffffff;
            }

            .form-subtitle {
                color: rgba(255, 255, 255, 0.8);
            }

            .form-label {
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

            .input-icon {
                color: rgba(255, 255, 255, 0.7);
            }

            .register-link {
                color: rgba(255, 255, 255, 0.9);
            }

            .register-link a {
                color: #00a8cc;
            }

            .register-link a:hover {
                color: #ffffff;
            }
        }
    </style>
</head>

<body>
    <!-- Partículas flotantes de fondo -->
    <div class="floating-particles">
        <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 20%; animation-delay: 2s;"></div>
        <div class="particle" style="left: 30%; animation-delay: 4s;"></div>
        <div class="particle" style="left: 40%; animation-delay: 6s;"></div>
        <div class="particle" style="left: 50%; animation-delay: 8s;"></div>
        <div class="particle" style="left: 60%; animation-delay: 10s;"></div>
        <div class="particle" style="left: 70%; animation-delay: 12s;"></div>
        <div class="particle" style="left: 80%; animation-delay: 14s;"></div>
        <div class="particle" style="left: 90%; animation-delay: 16s;"></div>
    </div>

    <!-- Contenedor principal del login -->
    <div class="login-container">
        <div class="login-content">
            <!-- Panel Hero - Solo visible en desktop -->
            <div class="hero-panel">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="bi bi-book-half"></i>
                    </div>
                    <h1 class="hero-title">Sistema Biblioteca<br>BIT TEK</h1>
                    <p class="hero-subtitle">
                        La tecnología del saber, en cada bit.
                    </p>
                </div>

                <ul class="feature-list">
                    <li class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Gestión completa de préstamos</span>
                    </li>
                    <li class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Control de inventario en tiempo real</span>
                    </li>
                    <li class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Reportes y estadísticas avanzadas</span>
                    </li>

                </ul>
            </div>

            <!-- Panel del formulario -->
            <div class="form-panel">
                <div class="form-header">
                    <h2 class="form-title">Iniciar Sesión</h2>
                    <p class="form-subtitle">Accede a tu cuenta para gestionar la biblioteca</p>
                </div>

                <form id="loginForm" method="POST" action="{{ route('login.enviar') }}">
                    @csrf

                    <div class="form-group">
                        <label for="username" class="form-label">Email</label>
                        <div class="input-group">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="text"
                                   class="form-control with-icon"
                                   id="username"
                                   name="username"
                                   placeholder="tu.correo@tecsup.edu.pe"
                                   required
                                   autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
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

                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Iniciar Sesión
                    </button>
                </form>

                @if ($errors->has('login'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ $errors->first('login') }}
                    </div>
                @endif

                <div class="register-link">
                    <p class="mb-0">
                        ¿No tienes una cuenta?
                        <a href="{{ route('registro.form') }}">Regístrate aquí</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para efectos adicionales -->
    <script>
        // Animación del formulario al cargar
        document.addEventListener('DOMContentLoaded', function() {
            const formPanel = document.querySelector('.form-panel');
            const heroPanel = document.querySelector('.hero-panel');
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

            // Animación de entrada (más sutil en móvil)
            setTimeout(() => {
                if (formPanel) {
                    formPanel.style.opacity = '0';
                    formPanel.style.transform = isMobile ? 'translateY(20px)' : 'translateX(30px)';
                    formPanel.style.transition = 'all 0.6s ease';

                    setTimeout(() => {
                        formPanel.style.opacity = '1';
                        formPanel.style.transform = isMobile ? 'translateY(0)' : 'translateX(0)';
                    }, 100);
                }

                if (heroPanel && !isMobile) {
                    heroPanel.style.opacity = '0';
                    heroPanel.style.transform = 'translateX(-30px)';
                    heroPanel.style.transition = 'all 0.6s ease';

                    setTimeout(() => {
                        heroPanel.style.opacity = '1';
                        heroPanel.style.transform = 'translateX(0)';
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

            // Efecto de loading al enviar formulario
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.querySelector('.btn-login');

            loginForm.addEventListener('submit', function() {
                loginBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Iniciando sesión...';
                loginBtn.disabled = true;
                loginBtn.style.opacity = '0.7';
            });

            // Ajustar partículas según el dispositivo
            const particles = document.querySelectorAll('.particle');
            if (isMobileDevice() || window.innerWidth <= 768) {
                particles.forEach(particle => {
                    particle.style.display = 'none';
                });
            }

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
                    .particle { animation-duration: 20s !important; }
                    .hero-panel::before { animation: none !important; }
                    * { transition-duration: 0.2s !important; }
                `;
                document.head.appendChild(style);
            }
        });

        // Agregar estilos CSS dinámicos para viewport en móvil
        const style = document.createElement('style');
        style.textContent = `
            @media (max-width: 768px) {
                .login-container {
                    min-height: calc(var(--vh, 1vh) * 100);
                }
            }

            .mobile-device .form-control:focus {
                transform: none !important;
            }

            .mobile-device .btn-login:hover {
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
