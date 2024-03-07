<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/php/config.php');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$nombre = $_POST['nombre'];
$aPaterno = $_POST['aPaterno'];
$aMaterno = $_POST['aMaterno'];
$username = $_POST['username'];
$password = $_POST['password'];
$userType = $_POST['userType'];

// Encripta la contraseña utilizando bcrypt
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Verifica si el usuario ya existe en la base de datos
$checkUsernameQuery = "SELECT * FROM usuarios WHERE username = '$username'";
$result = $conn->query($checkUsernameQuery);

if ($result->num_rows > 0) {
    $response = array("error" => true, "message" => "Nombre de usuario no disponible");
    echo json_encode($response);
} else {
    // El nombre de usuario no existe, procede con la inserción
    $sql = "INSERT INTO usuarios (nombre, aPaterno, aMaterno, username, password, userType) 
            VALUES ('$nombre', '$aPaterno', '$aMaterno', '$username', '$hashedPassword', '$userType')";

    if ($conn->query($sql) === TRUE) {
        $response = array("error" => false, "message" => "Registro exitoso, ahora puede iniciar sesión.");
        echo json_encode($response);
    } else {
        $response = array("error" => true, "message" => "Error al registrar: " . $conn->error);
        echo json_encode($response);
    }
}

$conn->close();
?>