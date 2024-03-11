<?php
function generarPDF($folio) {
    require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
    $connection = new mysqli($servername, $mysql_username, $mysql_password, $dbname);

    // Verifica la conexión
    if ($connection->connect_error) {
        die("La conexión falló: " . $connection->connect_error);
    }
    
    // Crear la consulta SQL SELECT con JOIN para obtener el nombre y apellido del usuario relacionado
    $sql = "SELECT mp.petName, mp.petSex, mp.petBreed, mp.petColor, mp.petPicture, mp.ownerName, mp.ownerCURP, mp.ownerINE, mp.ownerColony, mp.ownerAddress, CONCAT(u.nombre, ' ', u.apellido) AS nombreCompleto, DAY(mp.fechaRegistro) as dia, MONTH(mp.fechaRegistro) as mes, YEAR(mp.fechaRegistro) as anio
            FROM mascotasPropietarios mp
            INNER JOIN usuarios u ON mp.idTS = u.id
            WHERE mp.folio = $folio";
    
    // Ejecutar la consulta
    $result = $connection->query($sql);
    
    // Verificar si hay resultados y almacenarlos en variables si es así
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $petName = $row["petName"];
            $petSex = $row["petSex"];
            $petBreed = $row["petBreed"];
            $petColor = $row["petColor"];
            $petPathPicture = $row["petPicture"];
            $ownerName = $row["ownerName"];
            $ownerCURP = $row["ownerCURP"];
            $ownerINE = $row["ownerINE"];
            $ownerColony = $row["ownerColony"];
            $ownerAddress = $row["ownerAddress"];
            $tsName = $row["nombreCompleto"];
            $dia = (string)$row["dia"];
            $month = $row["mes"];
            $anio = (string)$row["anio"];

            $meses = [
                1 => "ENERO",
                2 => "FEBRERO",
                3 => "MARZO",
                4 => "ABRIL",
                5 => "MAYO",
                6 => "JUNIO",
                7 => "JULIO",
                8 => "AGOSTO",
                9 => "SEPTIEMBRE",
                10 => "OCTUMBRE",
                11 => "NOVIEMBRE",
                12 => "DICIEMBRE"
            ];

            $month = isset($meses[$month]) ? $meses[$month] : "Mes inválido";
            $folioActual = str_pad($folio, 6, "0", STR_PAD_LEFT);
            $tsUpper = strtoupper($tsName);
        }
        //Generar PDF
        $apiPath = str_replace($pathPicturesReplace, $pathChars, $_SERVER['DOCUMENT_ROOT']);
        $petPicture = str_replace($pathPicturesReplace, $pathChars, $petPathPicture);
        echo "<html><body><script src='$apiPath/API/generatePDF.js'></script>";
        echo "<script>createPDF('$folioActual', '$petName', '$petSex', '$petBreed', '$petColor', '$petPicture', '$ownerName', '$ownerCURP', '$ownerINE', '$ownerColony', '$ownerAddress', '$tsUpper', '$dia', '$month', '$anio');</script>";
        echo "</body></html>";

        $connection->close();
        return true;
        
    } else {
        echo "No se encontraron resultados para el folio $folio";
        $connection->close();
        die("Error de conversión... el folio que entro fue:" .$folio . "</br> Avisa al departamento de sistemas.");
        return false;
    }
}
?>