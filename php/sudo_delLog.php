<?php
$delPath = $_SERVER['DOCUMENT_ROOT'] . '/views/sudo/log';
$deleteResult = ""; 

// Verificar si el archivo existe
if (file_exists($delPath)) {
    // Eliminar el archivo
    if (unlink($delPath)) {
        $deleteResult = "El log fue eliminado correctamente.";
    } else {
        $deleteResult = "Error al intentar eliminar el log.";
    }
} else {
    $deleteResult = "El archivo de log no existe.";
}

echo json_encode($deleteResult, JSON_UNESCAPED_UNICODE);
?>
