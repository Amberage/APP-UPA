<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $queryResult = false;
    $esValido = false;
    $message = 'default';
    $folioRestore = $_POST['folioRestore'];

    // Conexión a la base de datos
    $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);
    
    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $queryBackup = "SELECT bp.*, CONCAT(u.nombre, ' ', u.apellido) AS tsName
                FROM backup_mascotasPropietarios bp
                INNER JOIN usuarios u ON bp.idTS = u.id
                WHERE bp.folio = $folioRestore";

    $result = $conn->query($queryBackup);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $backup_folio = $row["folio"];
            $backup_petName = $row["petName"];
            $backup_petBreed = $row["petBreed"];
            $backup_petColor = $row["petColor"];
            $backup_petSex = $row["petSex"];
            $backup_petPicture = $row["petPicture"];
            $backup_ownerName = $row["ownerName"];
            $backup_ownerINE = $row["ownerINE"];
            $backup_ownerCURP = $row["ownerCURP"];
            $backup_ownerColony = $row["ownerColony"];
            $backup_ownerAddress = $row["ownerAddress"];
            $backup_idTS = $row["idTS"];
            $backup_fechaRegistro = $row["fechaRegistro"];
            $backup_tsName = $row["tsName"]; 
        }
        $message = 'Se recuperaron datos del respaldo, pero no fue posible restaurar el acta. Favor de comunicarse con el departamento de sistemas. folioRestore: ' . $folioRestore;
        $queryResult = false;
        $esValido = true;
    } else {
        $message = "No se encontraron resultados para el folio proporcionado.";
        $queryResult = false;
        $esValido = false;
    }
    
    if($esValido == true) {
        if (copy($_SERVER['DOCUMENT_ROOT'] . $backup_petPicture, $_SERVER['DOCUMENT_ROOT'] . "/assets/petPictures/" . $backup_folio . ".jpg")) {
            $message = "La imagen se ha copiado correctamente.";
            $queryResult = false;
            $esValido = true;
        } else {
            $message =  "Ha ocurrido un error al copiar la imagen desde el respaldo. Favor de comunicarse con el departamento de sistemas. folioRestore: " . $folioRestore;
            $queryResult = false;
            $esValido = false;
        }
    }

    if($esValido == true) {
        $querySetVariable = "SET @recoveryBackup = 1";
        if ($conn->query($querySetVariable) === TRUE) {
            $message = "La sesión ha asegurado la integirdad de la base de datos.";
            $queryResult = false;
            $esValido = true;
        } else {
            $message =  "No se ha podido asegurar la integridad de la base de datos, la solicitud se ha cancelado por seguridad. Favor de comunicarse con el departamento de sistemas. folioRestore: " . $folioRestore;
            $queryResult = false;
            $esValido = false;
        }
    }

    if($esValido == true) {
        $petPicture =  str_replace('/backup', '', $backup_petPicture);
        $queryInsert = "INSERT INTO mascotasPropietarios (folio, petName, petBreed, petColor, petSex, petPicture, ownerName, ownerINE, ownerCURP, ownerColony, ownerAddress, idTS, fechaRegistro) 
                        VALUES ('$backup_folio', '$backup_petName', '$backup_petBreed', '$backup_petColor', '$backup_petSex', '$petPicture', '$backup_ownerName', '$backup_ownerINE', '$backup_ownerCURP', '$backup_ownerColony', '$backup_ownerAddress', '$backup_idTS', STR_TO_DATE('$backup_fechaRegistro', '%Y-%m-%d %H:%i:%s'))";
        
        // Ejecutar la consulta de inserción
        if ($conn->query($queryInsert) === TRUE) {
            $message = "El acta de $backup_petName ha sido restaurada con éxito con el folio $backup_folio para el usuario $backup_tsName.";
            $queryResult = true;
        } else {
            echo "Error al insertar datos en la tabla mascotasPropietarios: " . $conn->error . " Favor de comunicarse con el departamento de sistemas. folioRestore: $folioRestore";
            $queryResult = false;
        }
    }

    $conn->close();
    
    $responseData['message'] = $message;
    $responseData['queryResult'] = $queryResult;
    echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
}
?>