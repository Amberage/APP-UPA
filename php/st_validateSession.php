<?php
function validarSesion($ficheroJS) {
    if (!empty($ficheroJS) && is_string($ficheroJS)) {
        // Creamos la etiqueta script con la ruta al archivo JavaScript proporcionado
        echo '<script src="/javascript/' . htmlspecialchars($ficheroJS) . '.js"></script>';
    } else {
        die ('Error: Nombre de archivo no válido.');
    }
}
?>
