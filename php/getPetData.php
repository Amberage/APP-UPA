<?php
// Verificar si se recibió una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
    
    $folio = $_POST['folio'];
    $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $sql = "SELECT mp.petName, mp.petSex, mp.petBreed, mp.petColor, mp.petPicture, mp.ownerName, mp.ownerCURP, mp.ownerINE, mp.ownerColony, mp.ownerAddress, CONCAT(u.nombre, ' ', u.apellido) AS nombreTS, DAY(mp.fechaRegistro) as dia, MONTH(mp.fechaRegistro) as mes, YEAR(mp.fechaRegistro) as anio, mp.folio
            FROM mascotasPropietarios mp
            INNER JOIN usuarios u ON mp.idTS = u.id
            WHERE folio = $folio";

    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        // Crear un array para almacenar los datos
        $datos = array();
        while ($fila = $resultado->fetch_assoc()) {
            // Agregar cada fila al array de datos
            $datos[] = $fila;
        }
        // Codificar el array de datos como JSON y enviarlo al cliente
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    } else {
        echo "No se encontraron resultados.";
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>
