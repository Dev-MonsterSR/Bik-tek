<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bik Tek - Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"/>
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(120deg, #0b1c2b 0%, #1e3556 100%);
            background-attachment: fixed;
        }
        .register-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(11,28,43,0.13);
            background: rgba(255,255,255,0.98);
            max-width: 480px;
            margin: auto;
        }
        .register-icon {
            font-size: 3rem;
            color: #0B5ED7;
        }
        .form-control:focus {
            border-color: #0B1C2B;
            box-shadow: 0 0 0 0.18rem rgba(11, 28, 43, 0.18);
        }
        .btn-primary {
            background-color: #0B1C2B;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .btn-primary:hover {
            background-color: #0d2a3f;
        }
        .card-body {
            padding: 2.5rem 2rem;
        }
        @media (max-width: 576px) {
            .register-section {
                padding: 2rem 0 1rem 0;
            }
            .card-body {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>

    <!-- Registro -->
    <section class="register-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card register-card p-4">
                        <div class="text-center mb-4">
                            <div class="register-icon mb-2">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <h2 class="fw-bold mb-1" style="color:#0b1c2b;">Crea tu cuenta</h2>
                            <p class="text-muted mb-0">Completa el formulario para registrarte</p>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('registro.enviar') }}">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" value="{{ old('apellido') }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                        </form>
                        <div class="text-center mt-4">
                            <p class="mb-0">¿Ya tienes una cuenta? <a href="{{ route('login') }}" style="color:#0B5ED7;">Inicia sesión aquí</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
