<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
$conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

// Generar 100 registros de prueba
for ($i = 0; $i < 50; $i++) {
    $petName = "Mascota" . $i;
    $petBreed = "Raza" . $i;
    $petColor = "Color" . $i;
    $petSex = ($i % 2 == 0) ? 'MACHO' : 'HEMBRA';
    $petPicture = "/debug/database/test.jpg";
    $ownerName = "Propietario" . $i;
    $ownerINE = "INE" . $i;
    $ownerCURP = "CURP" . $i;
    $ownerColony = "Colonia" . $i;
    $ownerAddress = "Dirección" . $i;
    $idTS = 1; // ID del trabajador social, cámbialo según tus necesidades

    // Insertar el registro en la base de datos
    $sql = "INSERT INTO mascotasPropietarios (petName, petBreed, petColor, petSex, petPicture, ownerName, ownerINE, ownerCURP, ownerColony, ownerAddress, idTS)
    VALUES ('$petName', '$petBreed', '$petColor', '$petSex', '$petPicture', '$ownerName', '$ownerINE', '$ownerCURP', '$ownerColony', '$ownerAddress', '$idTS')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro creado correctamente<br>";
    } else {
        echo "Error al crear registro: " . $conn->error . "<br>";
    }
}

// Cierra la conexión
$conn->close();
?>