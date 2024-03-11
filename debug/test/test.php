<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
$conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);

// Número de resultados por página
$resultados_por_pagina = 15;

// Página actual (obtenida de la URL u otro método)
if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
} else {
    $pagina = 1;
}

// Calcular el desplazamiento (offset) basado en la página actual
$offset = ($pagina - 1) * $resultados_por_pagina;

// Crear la consulta SQL SELECT con JOIN para obtener el nombre y apellido del usuario relacionado
$sql = "SELECT mp.petName, mp.petSex, mp.petBreed, mp.petColor, mp.petPicture, mp.ownerName, mp.ownerCURP, mp.ownerINE, mp.ownerColony, mp.ownerAddress, CONCAT(u.nombre, ' ', u.apellido) AS nombreCompleto, DAY(mp.fechaRegistro) as dia, MONTH(mp.fechaRegistro) as mes, YEAR(mp.fechaRegistro) as anio, mp.folio
        FROM mascotasPropietarios mp
        INNER JOIN usuarios u ON mp.idTS = u.id
        LIMIT $offset, $resultados_por_pagina";

// Ejecutar la consulta SQL
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css"/>
    <link rel="stylesheet" href="test.css" />
    <title>Mostrar resultados</title>
</head>
<body>
    <?php
    // Mostrar los resultados en una tabla HTML
    if ($resultado->num_rows > 0) {
        echo "<table>";
        // Cabecera de la tabla
        echo "<tr><th>Folio</th><th>Pet Name</th><th>Sex</th><th>Breed</th><th>Color</th><th>Picture</th><th>Owner Name</th><th>Owner CURP</th><th>Owner INE</th><th>Owner Colony</th><th>Owner Address</th><th>Owner Complete Name</th><th>Fecha</th></tr>";
        // Output de los datos en la tabla
        while ($fila = $resultado->fetch_assoc()) {
            // Aquí construyes las filas de la tabla con los datos de la consulta
            echo "<tr>";
            echo "<td>" . $fila['folio'] . "</td>";
            echo "<td>" . $fila['petName'] . "</td>";
            echo "<td>" . $fila['petSex'] . "</td>";
            echo "<td>" . $fila['petBreed'] . "</td>";
            echo "<td>" . $fila['petColor'] . "</td>";
            echo "<td><img src='" . str_replace($pathPicturesReplace, $pathChars, $fila['petPicture']) . "' alt='Pet Picture'></td>";
            echo "<td>" . $fila['ownerName'] . "</td>";
            echo "<td>" . $fila['ownerCURP'] . "</td>";
            echo "<td>" . $fila['ownerINE'] . "</td>";
            echo "<td>" . $fila['ownerColony'] . "</td>";
            echo "<td>" . $fila['ownerAddress'] . "</td>";
            echo "<td>" . $fila['nombreCompleto'] . "</td>";
            echo "<td>" . $fila['dia'] . "/" . $fila['mes'] . "/" . $fila['anio'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron resultados.";
    }

    // Cerrar la conexión
    $conn->close();
    ?>
</body>
</html>
