<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
$delPath = ($_SERVER['DOCUMENT_ROOT'] . '/assets/petPictures');
$conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

// TRUNCATE TABLE mascotasPropietarios;
$sql_truncate = "TRUNCATE TABLE mascotasPropietarios";
if ($conn->query($sql_truncate) === TRUE) {
    echo "<script>alert('Tabla mascotasPropietarios truncada con éxito');</script>";
} else {
    echo "<script>alert('Error al truncar la tabla: " . $conn->error . "');</script>";
}

// ALTER TABLE mascotasPropietarios AUTO_INCREMENT = 1;
$sql_alter = "ALTER TABLE mascotasPropietarios AUTO_INCREMENT = 1";
if ($conn->query($sql_alter) === TRUE) {
    echo "<script>alert('Se ha reiniciado el valor de AUTO_INCREMENT con éxito');</script>";
} else {
    echo "<script>alert('Error al reiniciar AUTO_INCREMENT: " . $conn->error . "');</script>";
}

// Eliminar todo fichero existente dentro de la ruta $delPath
$files = glob($delPath . '/*'); // Obtener lista de archivos en el directorio
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file); // Eliminar archivo
        echo "Archivo eliminado: " . $file . "<br>"; // Mensaje sin alert
    }
}

$conn->close(); // Cerrar conexión
?>