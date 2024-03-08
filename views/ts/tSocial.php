<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="/assets/images/logo_muncipioVDCH.png" />
    <link rel="stylesheet" href="/css/styles.css" />
    <title>UPA | Trabajo Social</title>
</head>

<body>
    <header class="header">
        <nav>
            <div class="nav__bar">
                <div class="logo">
                    <a href="/index.html"><img src="/assets/images/logo_gobVDCH.png" alt="logo" /></a>
                </div>
                <div class="nav__menu__btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
            <ul class="nav__links" id="nav-links">
                <li><a href="/index.html"><span><i class="ri-home-4-fill"></i></span></a></li>
                <li><a href="#" id="killSession">Salir</a></li>
            </ul>
        </nav>
        <div class="section__container header__container" id="home">
            <p style="color: black;">UPA Valle de Chalco Solidaridad</p>
            <h1><span>Trabajador Social</span></h1>
        </div>
    </header>

    <section class="section__container room__container">
        <div class="about__content">
            <p class="section__subheader">PANEL DE TRABAJO SOCIAL</p>
        </div>
        <div class="room__grid">
            <a class="adminSection" href="#">
                <div class="room__card">
                    <div class="room__card__image">
                        <img src="/assets/images/admin_registerPet.png" alt="Registrar Mascotas" />
                    </div>
                    <div class="room__card__details">
                        <h4>Registrar Mascota</h4>
                        <p style="text-align: justify;"> Registra una nuevo mascota, <span class="impact">genera un acta</span>
                            para las mascotas que registres en el sistema.</p>
                    </div>
                </div>
            </a>
            
            <a class="adminSection" href="#">
                <div class="room__card">
                    <div class="room__card__image">
                        <img src="/assets/images/admin_adminRecords.png" alt="Modificar Actas" />
                    </div>
                    <div class="room__card__details">
                        <h4>Administrar Actas</h4>
                        <p style="text-align: justify;">¿Hay errores en algún acta? Aquí puedes <span
                                class="impact">modificar o descargar las actas de las mascotas</span> registradas por ti.</p>
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
    <script src="/javascript/sessionTools.js"></script>
</body>

</html>