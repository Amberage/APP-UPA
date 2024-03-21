<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/API/API.php');
$conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);

// Crear la consulta SQL SELECT con JOIN para obtener el nombre y apellido del usuario relacionado
$sql = "SELECT mp.petName, mp.petSex, mp.petBreed, mp.petColor, mp.petPicture, mp.ownerName, mp.ownerCURP, mp.ownerINE, mp.ownerColony, mp.ownerAddress, CONCAT(u.nombre, ' ', u.apellido) AS nombreCompleto, DAY(mp.fechaRegistro) as dia, MONTH(mp.fechaRegistro) as mes, YEAR(mp.fechaRegistro) as anio, mp.folio
        FROM mascotasPropietarios mp
        INNER JOIN usuarios u ON mp.idTS = u.id
        LIMIT 100";

// Ejecutar la consulta SQL
$resultado = $conn->query($sql);

if(isset($_GET['folio'])) {
    // Recuperar el folio de la URL
    $folio = $_GET['folio'];
    generarPDF($folio);
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
    <link rel="stylesheet" href="/css/tables.css" />
    <title>UPA | Consultar Actas</title>
    <script src="/API/imprimirActa.js"></script>
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
                <li><a href="/views/ts/tSocial.php">Herramientas</a></li>
                <li><a href="#" id="killSession">Salir</a></li>
            </ul>
        </nav>
        <div class="section__container header__container" id="home">
            <p style="color: black">UPA Valle de Chalco Solidaridad</p>
            <h1><span>Gestión de Actas</span></h1>
        </div>
    </header>
    <section class="tablaActas">
    <?php
    // Mostrar los resultados en una tabla HTML
    if ($resultado->num_rows > 0) {
        echo "<table class='tablaActas'>";
        // Cabecera de la tabla
        echo "<tr><th>Folio</th><th>Pet Name</th><th>Picture</th><th>Owner Name</th><th>Owner Colony</th><th>Fecha</th><th>Acción</th></tr>";
        // Output de los datos en la tabla
        while ($fila = $resultado->fetch_assoc()) {
            // Aquí construyes las filas de la tabla con los datos de la consulta
            echo "<tr>";
            echo "<td>" . $fila['folio'] . "</td>";
            echo "<td>" . $fila['petName'] . "</td>";
            echo "<td><img src='" . str_replace($pathPicturesReplace, $pathChars, $fila['petPicture']) . "' alt='Pet Picture' style='width: 100px; height: 100px;'></td>";
            echo "<td>" . $fila['ownerName'] . "</td>";
            echo "<td>" . $fila['ownerColony'] . "</td>";
            echo "<td>" . $fila['dia'] . "/" . $fila['mes'] . "/" . $fila['anio'] . "</td>";
            echo "<td>
                    <button class='btnAction' style='background-image: url(\"/assets/images/descargarActa.png\");' onclick=\"printPet('" . $fila['folio'] . "');\"></button>
                    <button class='btnAction' style='background-image: url(\"/assets/images/editarActa.png\");' onclick=\"alert('Función en desarrollo, maldito desesperado!');\"></button>
                </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron resultados.";
    }
    // Cerrar la conexión
    $conn->close();
    ?>
    </section>

    <footer class="footer" id="contact">
        <div class="section__container footer__container">
            <div class="footer__col">
                <div class="logo">
                    <a href="#home"><img src="/assets/images/logo_gob.png" alt="logo del municipio"
                            style="height: auto; width: 250px" /></a>
                </div>
                <p class="section__description">
                    En la UPA, cada latido cuenta: protegiendo y promoviendo el
                    bienestar animal con amor y compromiso.
                </p>
            </div>
            <div class="footer__col">
                <h4>H. AYUNTAMIENTO DE VALLE DE CHALCO</h4>
                <ul class="footer__links">
                    <li>
                        <a href="https://valledechalco.gob.mx/" target="_blank">Sitio Web</a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/GobiernoVDCHS" target="_blank">Facebook</a>
                    </li>
                    <li>
                        <a href="https://www.youtube.com/@GobValleDeChalcoSolidaridad" target="_blank">YouTube</a>
                    </li>
                    <li>
                        <a href="https://twitter.com/GobiernoVDCHS" target="_blank">Twitter</a>
                    </li>
                </ul>
            </div>
            <div class="footer__col">
                <h4>NUESTROS SERVICIOS</h4>
                <ul class="footer__links">
                    <li><a href="#services">Adopción de Mascotas</a></li>
                    <li><a href="#services">Esterilización de Mascotas</a></li>
                    <li>
                        <a href="#medicalServices">Servicios Médicos Veterinarios</a>
                    </li>
                    <li><a href="#">Consultar Actas de Adopción</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4>CONTÁCTANOS</h4>
                <ul class="footer__links">
                    <li>
                        <a href="mailto:upavalledechalco@gmail.com">upavalledechalco@gmail.com</a>
                    </li>
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
</body>

</html>