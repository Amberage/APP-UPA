<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');

// Variables de control de errores
$errorQuery = '';
$successfulQuery = '';
$esValido = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $idUser = $_POST['idUser'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $userType = $_POST['userType'];

    if($password == $confirmPassword) {
        $esValido = true;
    } else {
        $esValido = false;
        $errorQuery = "Las contraseñas no coinciden";
    }

    if($esValido == true) {
        // Iniciar la conexión a la BBDD
        $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error . "\n\n Comunícaselo al departamento de sistemas del municipio, lamentamos las molestias.");
        }

        // Verificar si ya existe un usuario con el mismo username
        $checkUsernameQuery = "SELECT * FROM usuarios WHERE username = '$username'";
        $result = mysqli_query($conn, $checkUsernameQuery);

        if (mysqli_num_rows($result) > 0) {
            $errorQuery = "El nombre de usuario ya está en uso";
        } else {
            // Hashear la contraseña antes de almacenarla en la base de datos
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Preparar la consulta SQL para insertar el nuevo usuario
            $sql = "INSERT INTO usuarios (id, nombre, apellido, username, password, userType) 
                    VALUES ('$idUser','$nombre', '$apellido', '$username', '$hashedPassword', '$userType')";

            // Ejecutar la consulta
            if (mysqli_query($conn, $sql)) {
                $successfulQuery = "Usuario registrado correctamente";
            } else {
                $errorQuery = "Error al registrar usuario: " . mysqli_error($conn) . "\n Comunícaselo al departamento de sistemas del municipio, lamentamos las molestias.";
            }
        }
        // Cerrar la conexión a la base de datos
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="/assets/images/logo_muncipioVDCH.png" />
    <title>Debug | Registrar Trabajadores</title>
</head>
<body>
    <h2>Registrar Trabajadores</h2>
    <?php
    if (!empty($errorQuery)) {
        echo '<p style="color:red;">' . $errorQuery . '</p>';
    }
    if (!empty($successfulQuery)) {
        echo '<p style="color:green;">' . $successfulQuery . '</p>';
    }
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div>
            <label for="idUser">idUser:</label>
            <input type="text" id="idUser" name="idUser" required>
        </div>
        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div>
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>
        </div>
        <div>
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="confirmPassword">Confirmar Contraseña:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>
        </div>
        <div>
            <label for="userType">Tipo de Usuario:</label>
            <select id="userType" name="userType">
                <option value="ts" selected>TS</option>
                <option value="adm">ADM</option>
            </select>
        <div>
            <button type="submit">Registrar Trabajador</button>
        </div>
        </div>
    </form>
</body>
</html>
