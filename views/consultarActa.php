<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/php/st_validateSession.php');
validarSesion('gnr_session');
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
  <title>UPA | Consultar Acta</title>
</head>

<body>
<div id="killSession"></div>
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
        <li><a href="/index.php">Inicio <span><i class="ri-home-4-fill"></i></span></a></li>
      </ul>
    </nav>
    <div class="section__container header__container" id="home">
      <p style="color: black;">UPA Valle de Chalco Solidaridad</p>
      <h1><span>Consultar Acta</span></h1>
    </div>
  </header>

  <!-- Ingresar login aqui abajo -->
  <section class="login" style="animation: showSlow 1s forwards;">
    <div class="form-box">
      <div class="form-value">
        <form autocomplete="off">
          <h2 class="login">¡Busca tu Acta!</h2>
          <div class="inputbox">
            <ion-icon name="paw"></ion-icon>
            <input id="folio" type="text" required maxlength="6" onkeydown="return killStrings(event);" onpaste="return false">
            <label>FOLIO</label>
          </div>
          <div class="inputbox">
            <ion-icon name="id-card"></ion-icon>
            <input id="ine" type="text" required maxlength="18" onkeydown="return allowInput(event);" onpaste="return false" oninput="upperCase(this);">
            <label>NÚMERO DE INE</label>
          </div>
        </form>
        <div style="margin-top: 20px;"><button class="login" type="button" onClick="search();">Buscar</button></div>
        <div class="returnError" style="margin-top:10px" id="errorMsg"></div>
      </div>
    </div>
  </section>
  <!-- Ingresar login aqui arriba -->

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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="/API/libraries/jquery.min.js"></script>
  <script src="/API/libraries/toastr.min.js"></script>
  <script src="/javascript/gnr_buscarActa.js"></script>
  <script src="/API/imprimirActa.js"></script>
</body>

</html>