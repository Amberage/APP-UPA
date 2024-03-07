<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');

// Variables de control de errores
$errorQuery = '';
$successfulQuery = '';
$esValido = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $userType = 'adm';

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
            $sql = "INSERT INTO usuarios (nombre, apellido, username, password, userType) 
                    VALUES ('$nombre', '$apellido', '$username', '$hashedPassword', '$userType')";

            // Ejecutar la consulta
            if (mysqli_query($conn, $sql)) {
                $successfulQuery = "Usuario registrado correctamente.";
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
<html lang="en">
<head>
  <link rel="stylesheet" href="test.css">
  <title>HASH TECHIE OFFICIAL</title>
</head>
<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" autocomplete="off">

                    <div class="inputbox">
                        <input type="text" name="nombre" id="nameUser" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,25}" required title="Solo letras y espacios">
                        <label>Nombre</label>
                    </div>

                    <div class="inputbox">
                        <input type="text" name="apellido" id="lastnameUser" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,40}" required title="Solo letras y espacios">
                        <label>Apellido</label>
                    </div>

                    <div class="inputbox">
                        <input type="text" name="username" minlength="4" maxlength="12" pattern="^[a-zA-Z0-9_.-]+$" required title="De 4 a 12 caracteres, se permiten letras (sin ñ), números, _ y .">
                        <label>Nombre de Usuario</label>
                    </div>

                    <div class="inputbox">
                        <input type="password" name="password" minlength="6" required title="Mínimo 6 caracteres">
                        <label>Contraseña</label>
                    </div>

                    <div class="inputbox">
                        <input type="password" name="confirmPassword" minlength="6" required>
                        <label>Contraseña</label>
                    </div>

                    <div><button class="login">Registrar Usuario</button></div>
                    <div class="returnError"><span><?php echo $errorQuery; ?></span></div>
                    <div class="returnSuccesful"><span><?php echo $successfulQuery; ?></span></div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
        document.getElementById('nameUser').addEventListener('keypress', function(event) {
            var char = event.which || event.keyCode;
            if (char >= 48 && char <= 57) {
                event.preventDefault();
            }
        });

        document.getElementById('lastnameUser').addEventListener('keypress', function(event) {
            var char = event.which || event.keyCode;
            if (char >= 48 && char <= 57) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>