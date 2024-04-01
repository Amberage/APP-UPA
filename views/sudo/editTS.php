<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
include ($_SERVER['DOCUMENT_ROOT'] . '/php/st_validateSession.php');
validarSesion('adm_session');

// Verifica si se recibió el parámetro 'idTS'
if (isset($_GET['idTS'])) {
    $idTS = $_GET['idTS'];

    if($idTS >= 1 && $idTS <= 3) {
        $visibilityUserType = "none";
    } else {
        $visibilityUserType = "block";
    }

    if($idTS == 1) {
        $visibilityPassword = "none";
    } else {
        $visibilityPassword = "block";
    }

    
    $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);
    
    // Verificar la conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Preparar la consulta SQL
    $sql = "SELECT nombre, apellido, username, userType FROM `usuarios` WHERE id = $idTS";
    
    // Ejecutar la consulta
    $result = $conn->query($sql);
    
    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Almacenar los datos en variables
        $row = $result->fetch_assoc();
        $bd_nombre = $row['nombre'];
        $bd_apellido = $row['apellido'];
        $bd_username = $row['username'];
        $bd_userType = $row['userType'];
    } else {
        //die("Error del servidor: Se solicitó un ID inexistente, favor de comunicarse con el departamento de sistemas.");
        header("Location: /views/sudo/viewTS.php");
    }
    // Cerrar la conexión
    $conn->close();
} else {
    // Si no se recibe el parámetro 'idTS', muestra un mensaje de error o redirecciona al usuario
    //die("Error en el servidor: Error del 'GET' en la modificación de mascotas, favor de comunicarse con el departamento de sistemas.");
    header("Location: /views/sudo/viewTS.php");
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="/assets/images/logo_muncipioVDCH.png" />
    <link rel="stylesheet" href="/css/styles.css" />
    <link rel="stylesheet" href="/css/petAdd.css" />
    <link rel="stylesheet" href="/css/login.css" />
    <title>SU | Editar Trabajador</title>
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
                <li><a href="/views/sudo/dashboard.php">Superadministrador</a></li>
                <li><a href="/views/admin/dashboard.php">Administrador</a></li>
                <li><a href="#" id="killSession" style="color: #ba1934; font-weight: bold;">Cerrar Sesión</a></li>
            </ul>
        </nav>
        <div class="section__container header__container" id="home">
            <p style="color: black;">Modo Super Administrador</p>
            <h1><span>Editar Usuarios</span></h1>
        </div>
    </header>

    <!-- Ingresar registro aqui abajo -->
    <section class="login" style="padding-bottom: 150px; padding-top: 130px; animation: showSlow 1s forwards;">
    <div class="form-box">
            <div class="form-value">
                <form autocomplete="off">
                <input type="text" name="idTS" id="idTS" value="<?php echo $idTS ?>" style="display: none;"/>
                    <div class="inputbox">
                        <input type="text" id="tsName" value="<?php echo isset($bd_nombre) ? $bd_nombre : ''; ?>" required maxlength="50"/>
                        <label>Nombre</label>
                    </div>

                    <div class="inputbox">
                        <input type="text" id="tsLastname" value="<?php echo isset($bd_apellido) ? $bd_apellido : ''; ?>" required maxlength="50"/>
                        <label>Apellido</label>
                    </div>

                    <div class="inputbox">
                        <input type="text" id="username" value="<?php echo isset($bd_username) ? $bd_username : ''; ?>" required onkeydown="return killSpace(event);" onpaste="return false" maxlength="12">
                        <label>Nombre de Usuario</label>
                    </div>

                    <div class="selectBox" style="display: <?php echo $visibilityUserType ?>">
                        <select name="userType" id="userType">
                            <option value="">Seleccione Cuenta</option>
                            <option value="adm" <?php if(isset($bd_userType) && $bd_userType == 'adm') echo 'selected="selected"'; ?>>Administrador</option>
                            <option value="ts" <?php if(isset($bd_userType) && $bd_userType == 'ts') echo 'selected="selected"'; ?>>Trabajador Social</option>
                        </select>
                        <label>Tipo de Cuenta</label>
                    </div>

                    <div class="pwdEdit" style="text-align: center; display: <?php echo $visibilityPassword ?>">
                        <label style="color: #9d2348;"><input type="checkbox" id="editPassword" onClick="changePassword();"> Modificar Contraseña</label>
                    </div>

                    <div class="inputbox" id="inputPassword" style="display: none;">
                        <input type="password" id="password"  disabled=true>
                        <label>Nueva Contraseña</label>
                    </div>

                    <div class="inputbox" id="inputConfirmPassword" style="display: none;">
                        <input type="password" id="confirmPassword" disabled=true>
                        <label>Confirmar Nueva Contraseña</label>
                    </div>
                </form>
                
                <div class="returnError" style="margin-top: 0px;" id="errorMsg"></div>
                <div class="options" style="margin-top: -5px;">
                    <div><button class="loginDisabled" id="editButton" style="width: 240px; margin-top: 15px;" type="button" onClick="editTS()">Modificar Usuario</button></div>
                    <div><button class="cancel" style="width: 240px; margin-top: 10px;" onClick="cancelTS();">Volver</button></div>
                </div>
                <div class="returnSuccesful" id="successMsg"></div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/javascript/sudo_editTS.js"></script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="/javascript/indexAnimations.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>