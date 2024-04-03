<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/php/st_validateSession.php');
validarSesion('adm_session');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="/assets/images/logo_muncipioVDCH.png" />
    <link rel="stylesheet" href="/css/styles.css" />
    <title>UPA | Superadministrador</title>
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
                <li><a href="/views/admin/dashboard.php">Volver a Administración</a></li>
                <li><a href="#" id="killSession" style="color: #ba1934; font-weight: bold;">Salir</a></li>
            </ul>
        </nav>
        <div class="section__container header__container" id="home">
            <p style="color: black;">UPA Valle de Chalco Solidaridad</p>
            <h1 style="font-size: 3em;"><span>Super Administrador</span></h1>
        </div>
    </header>

    <section class="section__container room__container" id="main">
        <div class="about__content">
            <p class="section__subheader">PANEL DE SUPER ADMINISTRACIÓN</p>
        </div>
        <div class="room__grid">
            <a class="adminSection" href="#home" onClick="resetDB()">
                <div class="room__card">
                    <div class="room__card__image">
                        <img src="/assets/images/sudo_restoreBD.png" alt="Registrar Trabajadores Sociales" />
                    </div>
                    <div class="room__card__details">
                        <h4>Reiniciar Base de Datos</h4>
                        <p style="text-align: justify;"> Reinicia la base de datos, <span class="impact">los trabajadores y las actas se perderán.</span>
                            </p>
                    </div>
                </div>
            </a>

            <a class="adminSection" href="/views/sudo/addTS.php">
                <div class="room__card">
                    <div class="room__card__image">
                        <img src="/assets/images/admin_registerUser.png" alt="Registrar Trabajadores Sociales" />
                    </div>
                    <div class="room__card__details">
                        <h4>Registrar Usuarios</h4>
                        <p style="text-align: justify;"> Registra usuarios, <span class="impact">ignorando el control de errores.</span>
                            </p>
                    </div>
                </div>
            </a>

            <a class="adminSection" href="/views/sudo/viewTS.php">
                <div class="room__card">
                    <div class="room__card__image">
                        <img src="/assets/images/admin_adminUser.png" alt="Registrar Trabajadores Sociales" />
                    </div>
                    <div class="room__card__details">
                        <h4>Editar Usuarios</h4>
                        <p style="text-align: justify;"> Editar usuarios, <span class="impact">ignorando el control de errores.</span>
                            </p>
                    </div>
                </div>
            </a>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/javascript/sudo_dashboard.js"></script>
</body>

</html>