
document.addEventListener('DOMContentLoaded', () => {
    // Selecciona el botón para alternar el sidebar y el contenedor principal
    const toggleButton = document.getElementById('toggle-sidebar-btn');
    const dashboardContainer = document.querySelector('.dashboard-container');

    // Verifica que los elementos existan antes de agregar el event listener
    if (toggleButton && dashboardContainer) {
        toggleButton.addEventListener('click', () => {
            // Alterna la clase 'sidebar-open' en el contenedor
            dashboardContainer.classList.toggle('sidebar-open');
        });
    }
});
