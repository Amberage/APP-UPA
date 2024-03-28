<?php
/* include ($_SERVER['DOCUMENT_ROOT'] . '/php/st_validateSession.php');
validarSesion('gnr_session'); */
session_start();
if (isset($_SESSION["id"]) && isset($_SESSION["nombre"]) && isset($_SESSION["apellido"]) && isset($_SESSION["userType"])) {
  if($_SESSION["userType"] = 'ts') {
    header("Location: /views/ts/dashboard.php");
    exit;
  } else if ($_SESSION["userType"] = 'adm') {
    header("Location: /views/admin/dashboard.php");
    exit;
  } else {
    die("Error en el login, tipo de cuenta desconocida, favor de comunicarse con el departamento de sistemas.");
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
  <link rel="icon" type="image/png" href="/assets/images/logo_muncipioVDCH.png"/>
  <link rel="stylesheet" href="/css/styles.css" />
  <title>UPA | Valle de Chalco Solidaridad</title>
  <script src="/debug/showDebug.js"></script>
</head>

<body>
  <header class="header">
  <div id="killSession"></div>
    <nav>
      <div class="nav__bar">
        <div class="logo">
          <a href="#"><img src="/assets/images/logo_gobVDCH.png" alt="logo" /></a>
        </div>
        <div class="nav__menu__btn" id="menu-btn">
          <i class="ri-menu-line"></i>
        </div>
      </div>
      <ul class="nav__links" id="nav-links">
        <li><a href="#aboutUS">¿Quiénes somos?</a></li>
        <li><a href="#services">Servicios</a></li>
        <li><a href="#consultarActa">Consultar Acta</a></li>
        <li><a href="#contact">Contáctanos</a></li>
        <li><a href="/views/login.php" style="color: #ba1934; font-weight: bold;">Ingresar</a></li>
        <li id="debugMenu" style="display: none;"><a href="debug/debugPanel.php"><span><i class="ri-code-box-fill"></i></span></a></li>
      </ul>
    </nav>
    <div class="section__container header__container" id="home">
      <p style="color: black;">UPA Valle de Chalco Solidaridad</p>
      <h1>Unidad de Protección Animal<br />de <span>Valle de Chalco Solidaridad</span>.</h1>
    </div>
  </header>

  <section class="section__container about__container" id="aboutUS">
    <div class="about__image">
      <img src="/assets/images/about_upa.jpg" alt="about"  id="upa"/>
    </div>
    <div class="about__content">
      <p class="section__subheader">¿QUIÉNES SOMOS?</p>
      <h2 class="section__header">Unidad de Protección Animal</h2>
      <p class="section__description">
        En nuestro compromiso con el bienestar animal, nos dedicamos apasionadamente a rescatar a las mascotas abandonadas en Valle de Chalco Solidaridad. Donde cada día, trabajamos incansablemente para brindarles atención, cuidado y amor.
      </p>
      <p class="section__description">
        Desde el momento en que llegan a nuestras instalaciones, estas mascotas reciben el tratamiento y la atención que merecen. Nuestro equipo de voluntarios y profesionales trabaja arduamente para asegurarse de que cada animal se recupere plenamente y esté listo para encontrar un nuevo hogar lleno de amor.
      </p>
      <div style="text-align: center;">
        <p class="section__description" style="text-align: center;">Visitanos en...</p>
        <div class="about__btn">
          <button class="btn" onclick="window.open('https://www.facebook.com/UPAvalledechalco', '_blank')">Facebook</button>
          <button class="btn" onclick="window.open('https://www.instagram.com/upa_valledechalco', '_blank')">Instagram</button>
          <button style="margin-top: 5px;" class="btn" onclick="window.open('https://maps.app.goo.gl/Kr1hdXWRYfJMzRgD7', '_blank')">Instalaciones</button>
        </div>
      </div>
    </div>
  </section>

  <section class="section__container room__container" id="services">
    <div class="about__content">
      <p class="section__subheader">NUESTROS SERVICIOS</p>
      <h2 class="section__header">EN LA UPA PODRÁS...</h2>
    </div>
    <div class="room__grid">
      <div class="room__card">
        <div class="room__card__image">
          <img src="/assets/images/services_adopta.png" alt="Adopta tu mascota" />
        </div>
        <div class="room__card__details">
          <h4>Adoptar un nuevo amigo</h4>
          <p style="text-align: justify;"> Si estás en busca de adoptar a un nuevo integrante para tu familia, puedes visitarnos los días de voluntariado en la UPA, Martes, Jueves y Sábados.</p>
          <p style="text-align: center; color: var(--primary-color); font-weight: bold;">¡Te esperamos con mucho cariño!</p>
        </div>
      </div>
      <div class="room__card">
        <div class="room__card__image">
          <img src="/assets/images/services_esteriliza.png" alt="Esteriliza a tu mascota" />
        </div>
        <div class="room__card__details">
          <h4>Esterilizar a tu mascota</h4>
          <p style="text-align: justify;">Ofrecemos servicios de esterilización para garantizar el control de la población y el bienestar de tus mascotas, mantente al pendiente en nuestro Facebook para las proximas campañas en tu colonia.</p>
          <p style="text-align: center; color: var(--primary-color); font-weight: bold;">¡Ayúdanos a evitar el sufrimiento innecesario!</p>
        </div>
      </div>
      <div class="room__card">
        <div class="room__card__image">
          <img src="/assets/images/services_dona.png" alt="Dona alimentos para mascotas" />
        </div>
        <div class="room__card__details">
          <h4>Donar alimento para mascotas</h4>
          <p style="text-align: justify;">Muchas mascotas dependen de la generosidad de personas como tú para sobrevivir. Tu donación puede marcar la diferencia en la vida de una mascota necesitada.</p>
          <p style="text-align: center; color: var(--primary-color); font-weight: bold;">¡Alimenta su esperanza!</p>
        </div>
      </div>
    </div>
  </section>

  <section class="service" id="medicalServices">
    <div class="section__container service__container">
      <div class="service__content">
        <p class="section__subheader">Además también contamos con...</p>
        <ul class="service__list">
          <li>
            <span><i class="ri-syringe-fill"></i></span>
            Vacuna Antirrábica  GRATUITA 
          </li>
          <li>
            <span><i class="ri-nurse-fill"></i></span>
            Esterilización a bajo costo
          </li>
          <li>
            <span><i class="ri-medicine-bottle-fill"></i></span>
            Desparasitación a bajo costo
          </li>
          <li>
            <span><i class="ri-stethoscope-fill"></i></span>
            Consulta Médica GRATUITA
          </li>
        </ul>
      </div>
    </div>
  </section>

  <section class="section__container banner__container">
    <div class="banner__content">
      <div class="banner__card">
        <h4>600+</h4>
        <p>Mascotas Rescatadas</p>
      </div>
      <div class="banner__card">
        <h4>350+</h4>
        <p>Mascotas Esterilizadas</p>
      </div>
      <div class="banner__card">
        <h4>200+</h4>
        <p>Mascotas Adoptadas</p>
      </div>
    </div>
  </section>

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
          <a href="#home"><img src="/assets/images/logo_gob.png" alt="logo del municipio" style="height: auto; width: 250px;"/></a>
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
          <a href="https://www.facebook.com/UPAvalledechalco" target="_blank"><img src="/assets/images/facebook.png" alt="facebook"/></a>
          <a href="https://www.instagram.com/upa_valledechalco" target="_blank"><img src="/assets/images/instagram.png" alt="instagram"/></a>
          <a href="https://maps.app.goo.gl/Kr1hdXWRYfJMzRgD7" target="_blank"><img src="/assets/images/gmaps.png" alt="maps"/></a>
        </div>
      </div>
    </div>
    <div class="footer__bar">
      Valle de Chalco Solidaridad | Copyright © 2024
    </div>
  </footer>
  <script src="https://unpkg.com/scrollreveal"></script>
  <script src="/javascript/indexAnimations.js"></script>
</body>

</html>