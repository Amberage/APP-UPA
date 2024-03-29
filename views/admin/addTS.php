<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/php/st_validateSession.php');
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
    <link rel="stylesheet" href="/css/login.css" />
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
                <li><a href="/views/admin/dashboard.php">Administración</a></li>
                <li><a href="/views/admin/viewTS.php">Administrar Trabajadores</a></li>
                <li><a href="/views/admin/viewPets.php">Administrar Actas</a></li>
                <li><a href="#" id="killSession">Salir</a></li>
            </ul>
        </nav>
        <div class="section__container header__container" id="home">
            <p style="color: black;">UPA Valle de Chalco Solidaridad</p>
            <h1><span>Registrar Usuarios</span></h1>
        </div>
    </header>

    <!-- Ingresar registro aqui abajo -->
    <section class="login" style="padding-bottom: 150px; padding-top: 100px; animation: showSlow 1s forwards;">
    <div class="form-box">
            <div class="form-value">
                <form autocomplete="off">
                    <div class="inputbox">
                        <input type="text" id="tsName" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,25}" required title="Solo letras y espacios" maxlength="25"/>
                        <label>Nombre</label>
                    </div>

                    <div class="inputbox">
                        <input type="text" id="tsLastname" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,40}" required title="Solo letras y espacios" maxlength="40"/>
                        <label>Apellido</label>
                    </div>

                    <div class="inputbox">
                        <input type="text" id="username" pattern="[a-zA-Z0-9_.]{4,12}" required title="Mínimo 4 caracteres, se permiten letras (sin ñ), números, _ y .">
                        <label>Nombre de Usuario</label>
                    </div>

                    <div class="inputbox">
                        <input type="password" id="password" minlength="6" pattern=".{6,36}" required title="Mínimo 6 caracteres, maximo 64 caracteres">
                        <label>Contraseña</label>
                    </div>

                    <div class="inputbox">
                        <input type="password" id="confirmPassword" minlength="6" pattern=".{6,36}" required>
                        <label>Confirmar Contraseña</label>
                    </div>
                </form>
                <div class="options">
                    <div><button class="login" style="width: 240px;" type="button" onClick="sendTS()">Registrar Usuario</button></div>
                    <div><button class="cancel" style="width: 240px; margin-top: 10px;" onClick="back();">Volver</button></div>
                </div>
                <div class="returnSuccesful" id="successMsg"></div>
                <div class="returnError" style="margin-top: 0px;" id="errorMsg"></div>
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
    <script src="/javascript/adm_addTS.js"></script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="/javascript/indexAnimations.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>