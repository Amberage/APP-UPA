<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
$responseLogin = [];
$responseLogin['successLog'] = false;
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Crear conexión
    $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        $responseLogin['errorMsg'] = "Error de conexión en login: " . $conn->connect_error . "\n\n Comunícaselo al departamento de sistemas del municipio, lamentamos las molestias.";
        die("Error de conexión en login: " . $conn->connect_error . "\n\n Comunícaselo al departamento de sistemas del municipio, lamentamos las molestias.");
    }

    // Obtener datos del formulario
    $username = $_POST["username"];
    $password = $_POST["password"];

    if(isset($_POST['sessionTime']) && $_POST['sessionTime'] == 'on') {
        // El checkbox está marcado
        $tiempoSesion = 2592000;
    } else {
        // El checkbox no está marcado
        $tiempoSesion = 0;
    }

    // Consulta SQL para obtener la contraseña hasheada del usuario
    $sql = "SELECT id, nombre, apellido, password, userType FROM usuarios WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Usuario encontrado, verificar la contraseña
        $row = $result->fetch_assoc();
        $hashedPassword = $row["password"];

        if (password_verify($password, $hashedPassword)) {
            // Establecer persistencia de sesión
            
            ini_set('session.gc_maxlifetime', $tiempoSesion);
            session_set_cookie_params($tiempoSesion, '/');
            session_start();
            
            // Contraseña correcta, iniciar sesión
            $_SESSION["id"] = $row["id"];
            $_SESSION["nombre"] = $row["nombre"];
            $_SESSION["apellido"] = $row["apellido"];
            $_SESSION["userType"] = $row["userType"];

            $responseLogin['userType'] = $_SESSION["userType"];
            $responseLogin['successLog'] = true;
            $responseLogin['errorMsg'] = '';

        } else {
            // Contraseña incorrecta
            $responseLogin['errorMsg'] = 'Usuario o contraseña incorrectos.';
            $responseLogin['successLog'] = false;
        }
    } else {
        // Usuario no encontrado
        $responseLogin['errorMsg'] = 'Usuario o contraseña incorrectos.';
        $responseLogin['successLog'] = false;
    }

    // Cerrar conexión
    $conn->close();
    echo json_encode($responseLogin, JSON_UNESCAPED_UNICODE);
}
?>
