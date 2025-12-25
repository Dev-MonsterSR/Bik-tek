/**
 * Hand Control Initialization Script
 * This script initializes the hand controller and sets up the toggle button
 */

// Initialize hand controller
let handController = null;

document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('hand-control-toggle');
    
    if (!toggleBtn) {
        console.warn('Hand control toggle button not found');
        return;
    }
    
    toggleBtn.addEventListener('click', async function() {
        if (!handController) {
            handController = new HandController();
        }
        
        try {
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
        } catch (error) {
            console.error('Error toggling hand control:', error);
            alert('Error al activar el control por gestos. Asegúrate de permitir el acceso a la cámara.');
        }
    });
});
