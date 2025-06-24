<style>
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
</style>

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
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() === 'inicio' ? 'active' : '' }}" href="{{ route('inicio') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() === 'libros.index' ? 'active' : '' }}" href="{{ route('libros.index') }}">Catálogo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() === 'servicios' ? 'active' : '' }}" href="{{ route('servicios') }}">Servicios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() === 'contacto' ? 'active' : '' }}" href="{{ route('contacto') }}">Contacto</a>
                </li>
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
            </div>
        </div>
    </div>
</nav>

