<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/php/st_validateSession.php');
validarSesion('ts_session');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="/assets/images/logo_muncipioVDCH.png" />
    <link rel="stylesheet" href="/css/styles.css" />
    <link rel="stylesheet" href="/css/petAdd.css"/>
    <title>UPA | Generar Acta</title>
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
                <li><a href="/views/ts/dashboard.php">Inicio</a></li>
                <li><a href="/views/ts/viewPets.php">Ver Actas</a></li>
                <li><a href="#" id="killSession" style="color: #ba1934; font-weight: bold;">Salir</a></li>
            </ul>
        </nav>
        <div class="section__container header__container" id="home">
            <p style="color: black">UPA Valle de Chalco Solidaridad</p>
            <h1><span>Generar Acta</span></h1>
        </div>
    </header>

    <!-- Ingresar registro aqui abajo -->
    <section class="login" style="padding-bottom: 150px; padding-top: 100px; animation: showSlow 1s forwards;">
        <div class="form-box">
            <div class="form-value">
            <form autocomplete="off" id="petDataForm">
                    <div class="twoColumns">
                        <!-- !DATOS DE LA MASCOTA -->
                        <div class="petData">
                            <h2 class="form" style="color: #666c6c;">Mascota</h2>
                            <hr />
                            <div class="inputbox">
                                <input type="text" name="petName" id="petName" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,25}"
                                    required title="Solo letras y espacios" maxlength="25" onblur="upperCase(this);"/>
                                <label>Nombre Mascota</label>
                                
                            </div>

                            <div class="inputbox">
                                <input type="text" name="petBreed" id="petBreed" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,25}"
                                    required title="Solo letras y espacios" maxlength="25" onblur="upperCase(this);"/>
                                <label>Raza</label>
                            </div>

                            <div class="inputbox">
                                <input type="text" name="petColor" id="petColor" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,25}"
                                    required title="Solo letras y espacios" maxlength="25" onblur="upperCase(this);"/>
                                <label>Color</label>
                            </div>

                            <div class="selectBox">
                                <select name="petSex" id="petSex">
                                    <option value="">Seleccione Sexo</option>
                                    <option value="HEMBRA">Hembra</option>
                                    <option value="MACHO">Macho</option>
                                </select>
                                <label>Sexo</label>
                            </div>

                            <div class="inputbox" style="position: relative;">
                                <input class="fileBox" type="file" name="petPicture" id="petPicture" accept="image/*" required/>
                                <label style="top: 10px;">Foto</label>
                            </div>
                        </div>

                        <!-- !DATOS DEL PROPIETARIO -->
                        <div class="ownerData">
                            <h2 class="form" style="color: #666c6c;">Propietario</h2>
                            <hr />

                            <div class="inputbox">
                                <input type="text" name="ownerName" id="ownerName"
                                    pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,40}" required title="Solo letras y espacios"
                                    maxlength="40" onblur="upperCase(this);"/>
                                <label>Nombre Propietario</label>
                            </div>

                            <div class="inputbox">
                                <input type="text" name="ownerINE" id="ownerINE" required maxlength="18"
                                    required title="La clave de elector se compone de 18 caracteres" onblur="upperCase(this);"/>
                                <label>INE</label>
                            </div>

                            <div class="inputbox">
                                <input type="text" name="ownerCURP" id="ownerCURP" required maxlength="18"
                                    required title="El CURP se compone de 18 caracteres" onblur="upperCase(this);"/>
                                <label>CURP</label>
                            </div>
                            <input type="text" name="idTS" id="idTS" value="<?php echo $idTS?>" style="display: none;"/>

                            <div class="selectBox">
                                <select name="ownerColony" id="ownerColony">
                                    <option value="">Seleccione Colonia</option>
                                    <option value="AlFREDO BARANDA">Alfredo Baranda</option>
                                    <option value="ALFREDO DEL MAZO">Alfredo del Mazo</option>
                                    <option value="AMÉRICAS I">Américas I</option>
                                    <option value="AMÉRICAS II">Americas II</option>
                                    <option value="AMPLIACIÓN SANTA CATARINA">Ampliación Santa Catarina</option>
                                    <option value="AVÁNDARO">Avándaro</option>
                                    <option value="CARLOS SALINAS DE GORTARI">Carlos Salinas de Gortari</option>
                                    <option value="CERRO DEL MARQUÉS">Cerro del Marqués</option>
                                    <option value="CONCEPCIÓN">Concepción</option>
                                    <option value="DARÍO MARTÍNEZ I">Darío Martínez I</option>
                                    <option value="DARÍO MARTIÍNEZ II">Darío Martínez II</option>
                                    <option value="DEL CÁRMEN">Del Cármen</option>
                                    <option value="EL AGOSTADERO">El Agostadero</option>
                                    <option value="EL TRIUNFO">El Triunfo</option>
                                    <option value="EMILIANO ZAPATA">Emiliano Zapata</option>
                                    <option value="EX HACIENDA XICO I">Ex Hacienda Xico I</option>
                                    <option value="EX HACIENDA XICO II">Ex Hacienda Xico II</option>
                                    <option value="GEOVILLAS ASUNCIÓN">Geovillas Asunción</option>
                                    <option value="GUADALUPANA I">Guadalupana I</option>
                                    <option value="GUADALUPANA II">Guadalupana II</option>
                                    <option value="GUADALUPANA III">Guadalupana III</option>
                                    <option value="INDEPENDENCIA">Independencia</option>
                                    <option value="JARDÍN">Jardín</option>
                                    <option value="MARÍA ISABEL">María Isabel</option>
                                    <option value="NIÑOS HEROES I">Niños Heroes I</option>
                                    <option value="NIÑOS HEROES II">Niños Heroes II</option>
                                    <option value="PODER POPULAR">Poder Popular</option>
                                    <option value="PROVIDENCIA">Providencia</option>
                                    <option value="REAL SAN MARTIN">Real San Martin</option>
                                    <option value="SANTA CATARINA">Santa Catarina</option>
                                    <option value="SANTA CRUZ">Santa Cruz</option>
                                    <option value="SANTIAGO">Santiago</option>
                                    <option value="SAN ISIDRO">San Isidro</option>
                                    <option value="SAN JUAN TLALPIZAHUAC">San Juan Tlalpizahuac</option>
                                    <option value="SAN MIGUEL XICO I">San Miguel Xico I</option>
                                    <option value="SAN MIGUEL XICO II">San Miguel Xico II</option>
                                    <option value="SAN MIGUEL XICO III">San Miguel Xico III</option>
                                    <option value="SAN MIGUEL XICO IV">San Miguel Xico IV</option>
                                    <option value="SAN MARTIN XICO LA LAGUNA">San Martin Xico La Laguna</option>
                                    <option value="SAN MIGUEL LAS TABLAS">San Miguel Las Tablas</option>
                                </select>
                                <label>Colonia</label>
                            </div>

                            <div class="inputbox">
                                <input type="text" name="ownerAddress" id="ownerAddress" required maxlength="254" onblur="upperCase(this);"/>
                                <label>Domicilio</label>
                            </div>
                        </div>
                    </div>
                    <!--! Fin del div "Two Columns"  -->
                </form>
                <div class="options" style="margin-top: 10px;">
                    <div style="text-align: center;"><div><button class="login" style="width: 240px;" type="button" onclick="sendPet()">Registrar Mascota</button></div></div>
                    <div><button class="cancel" style="width: 240px; margin-top: 10px;" onClick="back();">Volver</button></div>
                </div>
                <div class="returnSuccesful" id="successMsg"></div>
                <div class="returnError" style="margin-top:-20px;" id="errorMsg"></div>
            </div>
        </div>
    </section>
    <!-- Ingresar registro aqui arriba -->

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/javascript/ts_addPets.js"></script>
    <script src="/API/imprimirActa.js"></script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="/javascript/indexAnimations.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>