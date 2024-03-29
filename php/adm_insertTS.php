<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variables de control de errores
    $responseData[] = '';
    $esValido = false;
    // Obtener los datos del formulario
    $nombre = $_POST['tsName'];
    $apellido = $_POST['tsLastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $userType = 'ts';

    if($password == $confirmPassword) {
        $esValido = true;
    } else {
        $esValido = false;
        $responseData['errorMsg'] = "Las contraseñas no coinciden";
    }

    if($esValido == true) {
        // Iniciar la conexión a la BBDD
        $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);

        if ($conn->connect_error) {
            $responseData['errorMsg'] = "Error de conexión: " . $conn->connect_error . "\n\n Comunícaselo al departamento de sistemas del municipio, lamentamos las molestias.";
            die("Error de conexión: " . $conn->connect_error . "\n\n Comunícaselo al departamento de sistemas del municipio, lamentamos las molestias.");
        }

        // Verificar si ya existe un usuario con el mismo username
        $checkUsernameQuery = "SELECT * FROM usuarios WHERE username = '$username'";
        $result = mysqli_query($conn, $checkUsernameQuery);

        if (mysqli_num_rows($result) > 0) {
            $responseData['errorMsg'] = "¡El nombre de usuario ya está en uso!";
            $responseData['resultQuery'] = false;
        } else {
            // Hashear la contraseña antes de almacenarla en la base de datos
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Preparar la consulta SQL para insertar el nuevo usuario
            $sql = "INSERT INTO usuarios (nombre, apellido, username, password, userType) 
                    VALUES ('$nombre', '$apellido', '$username', '$hashedPassword', '$userType')";

            // Ejecutar la consulta
            if (mysqli_query($conn, $sql)) {
                $responseData['successfulMssg'] = "¡". $nombre . " " . "ha sido registrado!";
                $responseData['resultQuery'] = true;
            } else {
                $responseData['errorMsg'] = "Error al registrar usuario: " . mysqli_error($conn) . "\n Comunícaselo al departamento de sistemas del municipio, lamentamos las molestias.";
                $responseData['resultQuery'] = false;
            }
        }
        // Cerrar la conexión a la base de datos
        mysqli_close($conn);
    }
    echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
}
?>