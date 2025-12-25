
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Biblioteca')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="{{ asset('css/hand-control.css') }}"/>
    @stack('styles')
</head>
<body>
    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Hand Control Toggle Button -->
    <button id="hand-control-toggle" class="btn btn-primary" title="Activar control por gestos">
        <i class="bi bi-hand-index"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- MediaPipe Hands Libraries -->
    <!-- Note: MediaPipe libraries are loaded from CDN for ease of deployment.
         For production environments, consider hosting these files locally
         or using npm packages with proper dependency management. -->
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/control_utils/control_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>
    
    <!-- Hand Control Scripts -->
    <script src="{{ asset('js/hand-control.js') }}"></script>
    <script src="{{ asset('js/hand-control-init.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
