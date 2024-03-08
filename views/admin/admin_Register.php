<?php
// Iniciar sesión si aún no está iniciada
session_start();

if ($_SESSION["userType"] != "adm") {
    header("Location: /index.php");
}
?>

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
    $userType = 'ts';

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
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="/assets/images/logo_muncipioVDCH.png" />
    <link rel="stylesheet" href="/css/login.css" />
    <link rel="stylesheet" href="/css/styles.css" />
    <title>UPA | Registrar Trabajadores</title>
</head>

<body>
    <header class="header">
        <nav>
            <div class="nav__bar">
                <div class="logo">
                    <a href="/index.php"><img src="/assets/images/logo_gobVDCH.png" alt="logo" /></a>
                </div>
                <div class="nav__menu__btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
            <ul class="nav__links" id="nav-links">
                <li><a href="/views/admin/admin.php">Administración</a></li>
                <li><a href="#" id="killSession">Salir</a></li>
            </ul>
        </nav>
        <div class="section__container header__container" id="home">
            <p style="color: black;">UPA Valle de Chalco Solidaridad</p>
            <h1><span>Registrar Usuarios</span></h1>
        </div>
    </header>

    <!-- Ingresar registro aqui abajo -->
    <section class="login" style="padding-bottom: 150px; padding-top: 100px;">
    <div class="form-box">
            <div class="form-value">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" autocomplete="off">

                    <div class="inputbox">
                        <input type="text" name="nombre" id="nameUser" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,25}" required title="Solo letras y espacios" maxlength="25"/>
                        <label>Nombre</label>
                    </div>

                    <div class="inputbox">
                        <input type="text" name="apellido" id="lastnameUser" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,40}" required title="Solo letras y espacios" maxlength="40"/>
                        <label>Apellido</label>
                    </div>

                    <div class="inputbox">
                        <input type="text" name="username" minlength="4" maxlength="12" pattern="^[a-zA-Z0-9_.-]+$" required title="Minimo 4 caracteres, se permiten letras (sin ñ), números, _ y .">
                        <label>Nombre de Usuario</label>
                    </div>

                    <div class="inputbox">
                        <input type="password" name="password" minlength="6" required title="Mínimo 6 caracteres">
                        <label>Contraseña</label>
                    </div>

                    <div class="inputbox">
                        <input type="password" name="confirmPassword" minlength="6" required>
                        <label>Confirmar Contraseña</label>
                    </div>

                    <div><button class="login">Registrar Usuario</button></div>
                    <div class="returnError"><span><?php echo $errorQuery; ?></span></div>
                    <div class="returnSuccesful"><span><?php echo $successfulQuery; ?></span></div>
                </form>
            </div>
        </div>
    </section>
    <!-- Ingresar registro aqui arriba -->

    <footer class="footer" id="contact">
        <div class="section__container footer__container">
            <div class="footer__col">
                <div class="logo">
                    <a href="#home"><img src="/assets/images/logo_gob.png" alt="logo del municipio"
                            style="height: auto; width: 250px;" /></a>
                </div>
                <p class="section__description">
                    En la UPA, cada latido cuenta: protegiendo y promoviendo el bienestar animal con amor y compromiso.
                </p>
            </div>
            <div class="footer__col">
                <h4>H. AYUNTAMIENTO DE VALLE DE CHALCO</h4>
                <ul class="footer__links">
                    <li><a href="https://valledechalco.gob.mx/" target="_blank">Sitio Web</a></li>
                    <li><a href="https://www.facebook.com/GobiernoVDCHS" target="_blank">Facebook</a></li>
                    <li><a href="https://www.youtube.com/@GobValleDeChalcoSolidaridad" target="_blank">YouTube</a></li>
                    <li><a href="https://twitter.com/GobiernoVDCHS" target="_blank">Twitter</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4>NUESTROS SERVICIOS</h4>
                <ul class="footer__links">
                    <li><a href="#services">Adopción de Mascotas</a></li>
                    <li><a href="#services">Esterilización de Mascotas</a></li>
                    <li><a href="#medicalServices">Servicios Médicos Veterinarios</a></li>
                    <li><a href="#">Consultar Actas de Adopción</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4>CONTÁCTANOS</h4>
                <ul class="footer__links">
                    <li><a href="mailto:upavalledechalco@gmail.com">upavalledechalco@gmail.com</a></li>
                    <li><a href="tel:5572513868">55 7251 3868</a></li>
                </ul>
                <div class="footer__socials">
                    <a href="https://www.facebook.com/UPAvalledechalco" target="_blank"><img
                            src="/assets/images/facebook.png" alt="facebook" /></a>
                    <a href="https://www.instagram.com/upa_valledechalco" target="_blank"><img
                            src="/assets/images/instagram.png" alt="instagram" /></a>
                    <a href="https://maps.app.goo.gl/Kr1hdXWRYfJMzRgD7" target="_blank"><img
                            src="/assets/images/gmaps.png" alt="maps" /></a>
                </div>
            </div>
        </div>
        <div class="footer__bar">
            Valle de Chalco Solidaridad | Copyright © 2024
        </div>
    </footer>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="/javascript/indexAnimations.js"></script>
    <script src="/javascript/sessionTools.js"></script>
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