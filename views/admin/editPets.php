<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
include ($_SERVER['DOCUMENT_ROOT'] . '/php/st_validateSession.php');
validarSesion('adm_session');

// Verifica si se recibió el parámetro 'petID'
if (isset($_GET['petID'])) {
    $petID = $_GET['petID'];
    $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);
    
    // Verificar la conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Preparar la consulta SQL
    $sql = "SELECT m.petName, m.petBreed, m.petColor, m.petSex, m.petPicture, m.ownerName, m.ownerINE, m.ownerCURP, m.ownerColony, m.ownerAddress, CONCAT(u.nombre, ' ', u.apellido) AS nameTS, m.idTS
            FROM `mascotasPropietarios` AS m
            INNER JOIN `usuarios` AS u ON m.idTS = u.id
            WHERE m.folio = $petID";
    
    // Ejecutar la consulta
    $result = $conn->query($sql);
    
    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Almacenar los datos en variables
        $row = $result->fetch_assoc();
        $bd_petName = $row['petName'];
        $bd_petBreed = $row['petBreed'];
        $bd_petColor = $row['petColor'];
        $bd_petSex = $row['petSex'];
        $bd_petPicture = $row['petPicture'];
        $bd_ownerName = $row['ownerName'];
        $bd_ownerINE = $row['ownerINE'];
        $bd_ownerCURP = $row['ownerCURP'];
        $bd_ownerColony = $row['ownerColony'];
        $bd_ownerAddress = $row['ownerAddress'];
        $bd_nameTS = $row['nameTS'];
        $bd_idTS = $row['idTS'];
    } else {
        // No se encontraron resultados, redirecciona al usuario
        header("Location: /views/adm/viewPets.php");
    }
    $result->free();

    $queryGetTS = "SELECT id AS idTS, CONCAT(nombre, ' ', apellido) AS nameTS FROM usuarios WHERE userType = 'ts' OR id = 3";
    $result = $conn->query($queryGetTS);

    $optionsHTML = `<option value="">Seleccione una Opción</option>`;
    while ($row = $result->fetch_assoc()) {
        $idTS = $row['idTS'];
        $nameTS = $row['nameTS'];
        $optionsHTML .= '<option value="' . $idTS . '"';
        if (isset($nameTS) && $nameTS == $bd_nameTS) {
            $optionsHTML .= ' selected';
        }
        $optionsHTML .= '>' . strtoupper($nameTS) . '</option>';
    }
    


    // Cerrar la conexión
    $conn->close();
} else {
    // Si no se recibe el parámetro 'petID', redirecciona al usuario
    header("Location: /views/adm/viewPets.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="/assets/images/logo_muncipioVDCH.png" />
    <link rel="stylesheet" href="/API/libraries/toastr.min.css">
    <link rel="stylesheet" href="/css/styles.css" />
    <link rel="stylesheet" href="/css/petAdd.css"/>
    <title>UPA | Modificar Acta</title>
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
                <li><a href="/views/ts/addPets.php">Crear Acta</a></li>
                <li><a href="/views/ts/viewPets.php">Ver Actas</a></li>
                <li><a href="#" id="killSession" style="color: #ba1934; font-weight: bold;">Salir</a></li>
            </ul>
        </nav>
        <div class="section__container header__container" id="home">
            <p style="color: black">UPA Valle de Chalco Solidaridad</p>
            <h1><span>Modificar Acta <?php echo isset($petID) ? $petID : ''; ?></span></h1>
        </div>
    </header>

    <!-- Ingresar formulario aqui abajo -->
    <section class="login" style="padding-bottom: 150px; padding-top: 150px; animation: showSlow 1s forwards">
        <div class="form-box">
            <div class="form-value">
            <form autocomplete="off">
                    <div class="twoColumns">
                        <input type="text" name="idTS" id="idTS" value=<?php echo $bd_idTS?> style="display: none;"/>
                        <input type="text" name="nameTS" id="nameTS" value=<?php echo $bd_nameTS?> style="display: none;"/>
                        <input type="text" name="folioActa" id="folioActa" value=<?php echo $petID?> style="display: none;"/>
                        <input type="text" name="old_petPicture" id="old_petPicture" value=<?php echo $bd_petPicture?> style="display: none;"/>
                        <!-- !DATOS DE LA MASCOTA -->
                        <div class="petData">
                            <h2 class="form" style="color: #666c6c;">Mascota</h2>
                            <hr />
                            <div class="inputbox">
                                <input type="text" name="petName" id="petName" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,25}"
                                    required title="Solo letras y espacios" maxlength="25" oninput="upperCase(this);"
                                    value="<?php echo isset($bd_petName) ? $bd_petName : ''; ?>"/>
                                <label>Nombre Mascota</label>
                            </div>

                            <div class="inputbox">
                                <input type="text" name="petBreed" id="petBreed" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,25}"
                                    required title="Solo letras y espacios" maxlength="25" oninput="upperCase(this);"
                                    value="<?php echo isset($bd_petBreed) ? $bd_petBreed : ''; ?>"/>
                                <label>Raza</label>
                            </div>

                            <div class="inputbox">
                                <input type="text" name="petColor" id="petColor" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,25}"
                                    required title="Solo letras y espacios" maxlength="25" oninput="upperCase(this);"
                                    value="<?php echo isset($bd_petColor) ? $bd_petColor : ''; ?>"/>
                                <label>Color</label>
                            </div>

                            <div class="selectBox">
                                <select name="petSex" id="petSex">
                                    <option value="">Seleccione Sexo</option>
                                    <option value="HEMBRA" <?php if(isset($bd_petSex) && $bd_petSex == 'HEMBRA') echo 'selected'; ?>>Hembra</option>
                                    <option value="MACHO" <?php if(isset($bd_petSex) && $bd_petSex == 'MACHO') echo 'selected'; ?>>Macho</option>
                                </select>
                                <label>Sexo</label>
                            </div>

                            <div class="inputbox" style="position: relative;">
                                <input class="fileBox" type="file" name="petPicture" id="petPicture" accept="image/*"/>
                                <label style="top: 10px;">Cambiar Foto</label>
                            </div>
                        </div>

                        <!-- !DATOS DEL PROPIETARIO -->
                        <div class="ownerData">
                            <h2 class="form" style="color: #666c6c;">Propietario</h2>
                            <hr />

                            <div class="inputbox">
                                <input type="text" name="ownerName" id="ownerName"
                                    pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,40}" required title="Solo letras y espacios"
                                    maxlength="40" oninput="upperCase(this);" value="<?php echo isset($bd_ownerName) ? $bd_ownerName : ''; ?>"/>
                                <label>Nombre Propietario</label>
                            </div>

                            <div class="inputbox">
                                <input type="text" name="ownerINE" id="ownerINE" required maxlength="18" oninput="return killSpace(event);" onpaste="return false"
                                    required title="La clave de elector se compone de 18 caracteres" oninput="upperCase(this);"
                                    value="<?php echo isset($bd_ownerINE) ? $bd_ownerINE : ''; ?>"/>
                                <label>INE</label>
                            </div>

                            <div class="inputbox">
                                <input type="text" name="ownerCURP" id="ownerCURP" required maxlength="18" oninput="return killSpace(event);" onpaste="return false"
                                    required title="El CURP se compone de 18 caracteres" oninput="upperCase(this);"
                                    value="<?php echo isset($bd_ownerCURP) ? $bd_ownerCURP : ''; ?>"/>
                                <label>CURP</label>
                            </div>

                            <div class="selectBox">
                                <select name="ownerColony" id="ownerColony">
                                    <option value="">Seleccione Colonia</option>
                                    <option value="ALFREDO BARANDA" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'ALFREDO BARANDA') echo 'selected'; ?>>Alfredo Baranda</option>
                                    <option value="ALFREDO DEL MAZO" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'ALFREDO DEL MAZO') echo 'selected'; ?>>Alfredo del Mazo</option>
                                    <option value="AMÉRICAS I" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'AMÉRICAS I') echo 'selected'; ?>>Américas I</option>
                                    <option value="AMÉRICAS II" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'AMÉRICAS II') echo 'selected'; ?>>Americas II</option>
                                    <option value="AMPLIACIÓN SANTA CATARINA" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'AMPLIACIÓN SANTA CATARINA') echo 'selected'; ?>>Ampliación Santa Catarina</option>
                                    <option value="AVÁNDARO" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'AVÁNDARO') echo 'selected'; ?>>Avándaro</option>
                                    <option value="CARLOS SALINAS DE GORTARI" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'CARLOS SALINAS DE GORTARI') echo 'selected'; ?>>Carlos Salinas de Gortari</option>
                                    <option value="CERRO DEL MARQUÉS" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'CERRO DEL MARQUÉS') echo 'selected'; ?>>Cerro del Marqués</option>
                                    <option value="CONCEPCIÓN" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'CONCEPCIÓN') echo 'selected'; ?>>Concepción</option>
                                    <option value="DARÍO MARTÍNEZ I" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'DARÍO MARTÍNEZ I') echo 'selected'; ?>>Darío Martínez I</option>
                                    <option value="DARÍO MARTIÍNEZ II" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'DARÍO MARTIÍNEZ II') echo 'selected'; ?>>Darío Martínez II</option>
                                    <option value="DEL CÁRMEN" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'DEL CÁRMEN') echo 'selected'; ?>>Del Cármen</option>
                                    <option value="EL AGOSTADERO" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'EL AGOSTADERO') echo 'selected'; ?>>El Agostadero</option>
                                    <option value="EL TRIUNFO" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'EL TRIUNFO') echo 'selected'; ?>>El Triunfo</option>
                                    <option value="EMILIANO ZAPATA" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'EMILIANO ZAPATA') echo 'selected'; ?>>Emiliano Zapata</option>
                                    <option value="EX HACIENDA XICO I" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'EX HACIENDA XICO I') echo 'selected'; ?>>Ex Hacienda Xico I</option>
                                    <option value="EX HACIENDA XICO II" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'EX HACIENDA XICO II') echo 'selected'; ?>>Ex Hacienda Xico II</option>
                                    <option value="GEOVILLAS ASUNCIÓN" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'GEOVILLAS ASUNCIÓN') echo 'selected'; ?>>Geovillas Asunción</option>
                                    <option value="GUADALUPANA I" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'GUADALUPANA I') echo 'selected'; ?>>Guadalupana I</option>
                                    <option value="GUADALUPANA II" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'GUADALUPANA II') echo 'selected'; ?>>Guadalupana II</option>
                                    <option value="GUADALUPANA III" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'GUADALUPANA III') echo 'selected'; ?>>Guadalupana III</option>
                                    <option value="INDEPENDENCIA" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'INDEPENDENCIA') echo 'selected'; ?>>Independencia</option>
                                    <option value="JARDÍN" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'JARDÍN') echo 'selected'; ?>>Jardín</option>
                                    <option value="MARÍA ISABEL" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'MARÍA ISABEL') echo 'selected'; ?>>María Isabel</option>
                                    <option value="NIÑOS HEROES I" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'NIÑOS HEROES I') echo 'selected'; ?>>Niños Heroes I</option>
                                    <option value="NIÑOS HEROES II" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'NIÑOS HEROES II') echo 'selected'; ?>>Niños Heroes II</option>
                                    <option value="PODER POPULAR" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'PODER POPULAR') echo 'selected'; ?>>Poder Popular</option>
                                    <option value="PROVIDENCIA" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'PROVIDENCIA') echo 'selected'; ?>>Providencia</option>
                                    <option value="REAL SAN MARTIN" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'REAL SAN MARTIN') echo 'selected'; ?>>Real San Martin</option>
                                    <option value="SANTA CATARINA" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'SANTA CATARINA') echo 'selected'; ?>>Santa Catarina</option>
                                    <option value="SANTA CRUZ" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'SANTA CRUZ') echo 'selected'; ?>>Santa Cruz</option>
                                    <option value="SANTIAGO" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'SANTIAGO') echo 'selected'; ?>>Santiago</option>
                                    <option value="SAN ISIDRO" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'SAN ISIDRO') echo 'selected'; ?>>San Isidro</option>
                                    <option value="SAN JUAN TLALPIZAHUAC" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'SAN JUAN TLALPIZAHUAC') echo 'selected'; ?>>San Juan Tlalpizahuac</option>
                                    <option value="SAN MIGUEL XICO I" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'SAN MIGUEL XICO I') echo 'selected'; ?>>San Miguel Xico I</option>
                                    <option value="SAN MIGUEL XICO II" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'SAN MIGUEL XICO II') echo 'selected'; ?>>San Miguel Xico II</option>
                                    <option value="SAN MIGUEL XICO III" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'SAN MIGUEL XICO III') echo 'selected'; ?>>San Miguel Xico III</option>
                                    <option value="SAN MIGUEL XICO IV" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'SAN MIGUEL XICO IV') echo 'selected'; ?>>San Miguel Xico IV</option>
                                    <option value="SAN MARTIN XICO LA LAGUNA" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'SAN MARTIN XICO LA LAGUNA') echo 'selected'; ?>>San Martin Xico La Laguna</option>
                                    <option value="SAN MIGUEL LAS TABLAS" <?php if(isset($bd_ownerColony) && $bd_ownerColony == 'SAN MIGUEL LAS TABLAS') echo 'selected'; ?>>San Miguel Las Tablas</option>
                                </select>
                                <label>Colonia</label>
                            </div>

                            <div class="inputbox">
                                <input type="text" name="ownerAddress" id="ownerAddress" required maxlength="254" oninput="upperCase(this);"
                                value="<?php echo isset($bd_ownerAddress) ? $bd_ownerAddress : ''; ?>"/>
                                <label>Domicilio</label>
                            </div>
                        </div>
                    </div>
                    <!--! Fin del div "Two Columns"  -->
                    <div class="selectBoxTS">
                                <select name="dataTS" id="dataTS">
                                    <?php echo $optionsHTML; ?>
                                </select>
                        <label>Reasignar Acta</label>
                    </div>
                </form>
                <div class="returnError" style="margin-top: 0px;" id="errorMsg"></div>
                <div class="options" style="margin-top: -5px;">
                    <div><button class="loginDisabled"style="width: 240px; margin-top: 15px;" type="button" id="sendPet" onClick="editPet();">Modificar Acta</button></div>
                    <div><button class="cancel" style="width: 240px; margin-top: 10px;" onClick="cancelPet();">Volver</button></div>
                </div>
                <div class="returnSuccesful" id="successMsg"></div>
            </div>
        </div>
    </section>
    <!-- Ingresar formulario aqui arriba -->

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
    <script src="/API/libraries/jquery.min.js"></script>
    <script src="/API/libraries/toastr.min.js"></script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="/javascript/indexAnimations.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/API/imprimirActa.js"></script>
    <script src="/javascript/adm_editPets.js"></script>
</body>

</html>