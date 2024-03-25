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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <section class="section__container banner__container" style="padding: 0;">
    <div class="banner__content">
      <div class="banner__card">
        <h4>Registros</h4>
        <div class="registros">
            <label for="numRegisters">Mostrar: </label>
            <select name="numRegisters" id="numRegisters">
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="25">25</option>
                <option value="35">35</option>
                <option value="50">50</option>
                <option value="75">75</option>
                <option value="100">100</option>
            </select>
            <label for="numRegisters">registros</label>
        </div>
        <div class="paginacion">
            <div id="nav-paginacion"></div>
        </div>
      </div>
      <div class="banner__card">
        <h4>Buscar</h4>
        <div class="searchbox">
            <ion-icon name="search-sharp"></ion-icon>
            <input type="text" name="searchData" id="searchData" placeholder="Ingrese busqueda..."/>
        </div>
      </div>
      <div class="banner__card">
        <h4>Actas</h4>
        <div><p id="lbl-total"></label></div>
      </div>
    </div>
  </section>

    <div class="tablaActas">
        <p></p>
        <table>
            <thead>
                <th>Folio</th>
                <th>Nombre</th>
                <th>Fotografía</th>
                <th>Propietario</th>
                <th>Colonia</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </thead>
            <tbody id="contentTable"></tbody>
        </table>
    </div>

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
    <script src="/javascript/delete.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="/javascript/loaderTable.js"></script>
</body>

</html>