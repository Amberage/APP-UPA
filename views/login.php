<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');

// Variable de control de mensajes de error
$errorQuery = '';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Crear conexión
    $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
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

            /* !Depuración
            // Crear un array con los datos de la sesión
            $sessionData = array(
                "id" => $_SESSION["id"],
                "nombre" => $_SESSION["nombre"],
                "apellido" => $_SESSION["apellido"],
                "userType" => $_SESSION["userType"],
                "tiempoSesion" => $tiempoSesion
            );

            // Convertir el array a formato JSON
            $jsonSessionData = json_encode($sessionData);
            die($jsonSessionData); */

            // Redirigir según el tipo de cuenta
            if ($row["userType"] == "adm") {
                header("Location: /views/admin/admin.php");
            } elseif ($row["userType"] == "ts") {
                header("Location: /views/ts/tSocial.php");
            } else {
                header("Location: /index.php");
            }
        } else {
            // Contraseña incorrecta
            $errorQuery = 'Usuario o contraseña incorrectos.';
        }
    } else {
        // Usuario no encontrado
        $errorQuery = 'Usuario o contraseña incorrectos.';
    }

    // Cerrar conexión
    $conn->close();
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
  <title>UPA | Ingresar</title>
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
        <li><a href="#consultarActa">Consultar Acta</a></li>
        <li><a href="/index.php"><span><i class="ri-home-4-fill"></i></span></a></li>
      </ul>
    </nav>
    <div class="section__container header__container" id="home">
      <p style="color: black;">UPA Valle de Chalco Solidaridad</p>
      <h1><span>Sistema de Control</span></h1>
    </div>
  </header>

  <!-- Ingresar login aqui abajo -->
  <section class="login">
    <div class="form-box">
      <div class="form-value">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" autocomplete="off">
          <h2 class="login">Ingresar</h2>
          <div class="inputbox">
            <ion-icon name="person-circle-outline"></ion-icon>
            <input name="username" type="text" required>
            <label>Nombre de Usuario</label>
          </div>
          <div class="inputbox">
            <ion-icon name="lock-closed-outline"></ion-icon>
            <input name="password" type="password" required>
            <label>Contraseña</label>
          </div>
          <div class="forget">
            <label style="color: #1a5c50;"><input type="checkbox" name="sessionTime"> Mantener sesión iniciada</label>
          </div>
          <div class="returnError"><span><?php echo $errorQuery; ?></span></div>
          <div><button class="login">Ingresar</button></div>
        </form>
      </div>
    </div>
  </section>
  <!-- Ingresar login aqui arriba -->

  <section class="explore" id="consultarActa">
    <p class="section__subheader">ADOPCIÓN</p>
    <h2 class="section__header">CONSULTA TU ACTA</h2>
    <div class="explore__bg">
      <div class="explore__content">
        <h4 style="text-align: justify;">¿Recientemente has adoptado una mascota y quieres consultar su acta?</h4>
        <button class="btn">Consulta el acta de tu mascota</button>
      </div>
    </div>
  </section>

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
          <a href="https://www.facebook.com/UPAvalledechalco" target="_blank"><img src="/assets/images/facebook.png"
              alt="facebook" /></a>
          <a href="https://www.instagram.com/upa_valledechalco" target="_blank"><img src="/assets/images/instagram.png"
              alt="instagram" /></a>
          <a href="https://maps.app.goo.gl/Kr1hdXWRYfJMzRgD7" target="_blank"><img src="/assets/images/gmaps.png"
              alt="maps" /></a>
        </div>
      </div>
    </div>
    <div class="footer__bar">
      Valle de Chalco Solidaridad | Copyright © 2024
    </div>
  </footer>
  <script src="https://unpkg.com/scrollreveal"></script>
  <script src="/javascript/indexAnimations.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>