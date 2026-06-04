document.addEventListener('DOMContentLoaded', () => {
    // Elementos del DOM
    const btnActivar = document.getElementById('btn-activar');
    const menuAccesibilidad = document.getElementById('accessibility-menu');
    const btnCerrarMenu = document.getElementById('btn-cerrar-menu');
    
    // Botones de opciones dentro del menú
    const optTextSize = document.getElementById('opt-text-size');
    const optContrast = document.getElementById('opt-contrast');
    const optDyslexia = document.getElementById('opt-dyslexia');

    // 1. Abrir el menú al dar click en "Activar"
    btnActivar.addEventListener('click', (e) => {
        e.stopPropagation(); // Evita que se cierre inmediatamente
        menuAccesibilidad.classList.remove('hidden');
    });

    // 2. Cerrar el menú
    btnCerrarMenu.addEventListener('click', () => {
        menuAccesibilidad.classList.add('hidden');
    });

    // 3. Lógica para "Aumentar Tamaño de Texto"
    optTextSize.addEventListener('click', () => {
        document.body.classList.toggle('large-text');
        // Pequeño feedback visual en el botón del menú
        optTextSize.classList.toggle('active-option');
    });

    // 4. Lógica para "Alto Contraste"
    optContrast.addEventListener('click', () => {
        document.body.classList.toggle('high-contrast');
    });

    // 5. Lógica para "Fuente Accesible (Dislexia)"
    optDyslexia.addEventListener('click', () => {
        document.body.classList.toggle('dyslexia-font');
    });

    // Cerrar menú si el usuario hace click afuera de él
    document.addEventListener('click', (e) => {
        if (!menuAccesibilidad.contains(e.target) && e.target !== btnActivar) {
            menuAccesibilidad.classList.add('hidden');
        }
    });
});