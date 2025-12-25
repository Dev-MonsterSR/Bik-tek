
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Biblioteca')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"/>
    @stack('styles')
</head>
<body>
    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Hand Control Toggle Button -->
    <button id="hand-control-toggle" class="btn btn-primary" style="
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 9998;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        transition: all 0.3s ease;
    " title="Activar control por gestos">
        <i class="bi bi-hand-index"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- MediaPipe Hands Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/control_utils/control_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>
    
    <!-- Hand Control Script -->
    <script src="{{ asset('js/hand-control.js') }}"></script>
    <script>
        // Initialize hand controller
        let handController = null;

        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('hand-control-toggle');
            
            toggleBtn.addEventListener('click', async function() {
                if (!handController) {
                    handController = new HandController();
                }
                
                await handController.toggle();
                
                // Update button appearance
                if (handController.enabled) {
                    toggleBtn.style.background = '#28a745';
                    toggleBtn.innerHTML = '<i class="bi bi-hand-index-fill"></i>';
                    toggleBtn.title = 'Desactivar control por gestos';
                } else {
                    toggleBtn.style.background = '#0B5ED7';
                    toggleBtn.innerHTML = '<i class="bi bi-hand-index"></i>';
                    toggleBtn.title = 'Activar control por gestos';
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
