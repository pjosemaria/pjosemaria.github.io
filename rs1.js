// Detectar cuando el dispositivo cambia de orientación
window.addEventListener('orientationchange', function() {
    if (window.innerWidth > window.innerHeight) {
        // Activar la orientación horizontal
        document.getElementById('video-player').play();
    }
});