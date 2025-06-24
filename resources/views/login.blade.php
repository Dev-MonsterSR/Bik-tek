
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bik Tek - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(120deg, #0b1c2b 0%, #1e3556 100%);
            background-attachment: fixed;
        }
        .login-hero {
            background: linear-gradient(rgba(11, 28, 43, 0.92), rgba(11, 28, 43, 0.85)), url("{{ asset('img/biblioteca-hero.jpg') }}");
            background-size: cover;
            background-position: center;
            color: white;
            padding: 4rem 0 2rem 0;
        }
        .login-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(11,28,43,0.18);
            background: rgba(255,255,255,0.98);
        }
        .login-icon {
            font-size: 3.5rem;
            color: #0B5ED7;
        }
        .form-control:focus {
            border-color: #0B1C2B;
            box-shadow: 0 0 0 0.18rem rgba(11, 28, 43, 0.18);
        }
        .btn-login {
            background-color: #0B1C2B;
            color: white;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .btn-login:hover {
            background-color: #0d2a3f;
            color: white;
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #6c757d;
            margin: 1.5rem 0;
        }
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }
        .divider::before {
            margin-right: 1rem;
        }
        .divider::after {
            margin-left: 1rem;
        }
        .card-body {
            padding: 2.5rem 2rem;
        }
        @media (max-width: 576px) {
            .login-hero {
                padding: 2rem 0 1rem 0;
            }
            .card-body {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Hero Section del Login -->
    <section class="login-hero text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-2">Inicia Sesión</h1>
                    <p class="lead mb-0">Accede a tu cuenta para gestionar préstamos, reservas y más</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Formulario de Login -->
    <section class="d-flex align-items-center justify-content-center" style="min-height: 70vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="card login-card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="login-icon mb-3">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <h2 class="fw-bold mb-1" style="color:#0b1c2b;">Bienvenido</h2>
                                <p class="text-muted mb-0">Ingresa tus credenciales para acceder</p>
                            </div>
                            <form id="loginForm" method="POST" action="{{ route('login.enviar') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="username" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="username" name="username" required autofocus>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-login w-100 py-2 mb-2">
                                    Iniciar Sesión
                                </button>
                            </form>
                            @if ($errors->has('login'))
                                <div class="alert alert-danger mt-2">
                                    {{ $errors->first('login') }}
                                </div>
                            @endif
                            <div class="text-center mt-4">
                                <p class="mb-0">¿No tienes una cuenta? <a href="{{ route('registro.form') }}" class="fw-semibold" style="color:#0B5ED7;">Regístrate aquí</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
