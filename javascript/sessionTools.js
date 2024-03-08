document.getElementById('killSession').addEventListener('click', function(event) {
    event.preventDefault(); // Evitar el comportamiento predeterminado del enlace (navegar a una nueva página)

    // Realizar una solicitud al servidor para eliminar la cookie
    // Esto puede hacerse a través de una redirección a un script PHP que elimine la cookie

    window.location.href = '/php/killSession.php'; // Redireccionar a un script PHP que elimine la cookie
});
