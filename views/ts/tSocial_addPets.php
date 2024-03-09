<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');

session_start();

 if ($_SESSION["userType"] != "ts") {
    header("Location: /index.php");
}

// Variables de control de errores
$tamanoMaximo = 20 * 1024 * 1024; // Tamaño máximo permitido en bytes (20MB)
$errorQuery = '';
$successfulQuery = '';
$esValido = false;
$idTS = $_SESSION["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos de la mascota
    $petName = strtoupper($_POST['petName']);
    $petBreed = strtoupper($_POST['petBreed']);
    $petColor = strtoupper($_POST['petColor']);
    $petSex = strtoupper($_POST['petSex']);
    // Datos del Propietario
    $ownerName = strtoupper($_POST['ownerName']);
    $ownerINE = strtoupper($_POST['ownerINE']);
    $ownerCURP = strtoupper($_POST['ownerCURP']);
    $ownerColony = strtoupper($_POST['ownerColony']);
    $ownerAddress = strtoupper($_POST['ownerAddress']);

    // Iniciar la conexión a la BBDD
    $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);

    //! Consultar y validar el último folio
    $queryFolio = "SELECT MAX(folio) AS lastFolio FROM mascotasPropietarios";
    $lastFolio = $conn->query($queryFolio);

    if ($lastFolio) {
        // Obtener el resultado como un array asociativo y Obtener el último folio
        $row = $lastFolio->fetch_assoc();
        $lastFolio = $row['lastFolio'];
        $folioActual= strval($lastFolio + 1);
        $esValido = true;
    } else {
        // Manejar el error si la consulta falla
        $lastFolio = -1; 
        $esValido = false;
        $errorQuery = "Error en la consulta del ultimo foloio: " . "</br>Informa al departamento de sistemas, lamentamos las molestias.";
    }

    //! Validar la imagen
    if ($esValido == true) {
        if ($_FILES["petPicture"]["size"] <= $tamanoMaximo) {

            // Ruta donde se guardará la imagen
            $path_petPictures = $_SERVER['DOCUMENT_ROOT'] . '/actas/petPictures/';
        
            // Nombre del archivo
            $fileName = $folioActual;
        
            // Ruta completa del archivo
            $savedPath = $path_petPictures . $fileName . ".jpg";
            $petPicture = $savedPath;
        
            // Obtenemos la información del archivo
            $tempFile = $_FILES["petPicture"]["tmp_name"];
        
            // Verificamos si es una imagen
            $typeImg = exif_imagetype($tempFile);
        
            // Permitir formatos de imagen JPEG, PNG, BMP
            if ($typeImg === IMAGETYPE_JPEG || $typeImg === IMAGETYPE_PNG) {
        
                // Creamos una imagen desde el archivo temporal
                switch ($typeImg) {
                    case IMAGETYPE_JPEG:
                        $oldImg = imagecreatefromjpeg($tempFile);
                        break;
                    case IMAGETYPE_PNG:
                        $oldImg = imagecreatefrompng($tempFile);
                        break;
                    default:
                        $errorQuery = "Formato de imagen no soportado.";
                        $esValido = false;
                        exit;
                }
        
                // Obtener información de orientación de la imagen
                $exif = exif_read_data($tempFile);
                if (!empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 3:
                            $oldImg = imagerotate($oldImg, 180, 0);
                            break;
                        case 6:
                            $oldImg = imagerotate($oldImg, -90, 0);
                            break;
                        case 8:
                            $oldImg = imagerotate($oldImg, 90, 0);
                            break;
                    }
                }
        
                // Redimensionamos la imagen a 200x200
                $newImg = imagecreatetruecolor(200, 200);
                imagecopyresampled($newImg, $oldImg, 0, 0, 0, 0, 200, 200, imagesx($oldImg), imagesy($oldImg));
        
                // Guardamos la imagen redimensionada como JPEG
                imagejpeg($newImg, $savedPath);
        
                // Liberamos memoria
                imagedestroy($newImg);
                imagedestroy($oldImg);
        
                //echo "La imagen se ha subido correctamente.";
                $esValido = true;
        
            } else {
                $errorQuery = "Formato de imagen no válido. Solo se permiten formatos JPEG y PNG.";
                $esValido = false;
            }
        
        } else {
            $errorQuery = "El tamaño del archivo excede el límite permitido (20MB).";
            $esValido = false;
        }
    }

    if($esValido == true) {
        // Query para insertar datos en la tabla mascotasPropietarios
        $query = "INSERT INTO mascotasPropietarios (petName, petBreed, petColor, petSex, petPicture, ownerName, ownerINE, ownerCURP, ownerColony, ownerAddress, idTS) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        if ($stmt = $conn->prepare($query)) {
            // Vincular variables a la declaración preparada como parámetros
            $stmt->bind_param("ssssssssssi", $petName, $petBreed, $petColor, $petSex, $petPicture, $ownerName, $ownerINE, $ownerCURP, $ownerColony, $ownerAddress, $idTS);

            // Ejecutar la declaración
            if ($stmt->execute()) {
                /* Se usará el metodo 2, ya que el metodo 1 es propenso a mandar registros duplicados si el usuario actualza el sitio despues de agregar un registro. */
                /* Metodo 1
                $successfulQuery = "Mascota insertada correctamente.";
                header("Location: /test/test.php"); */
                // Metodo 2
                echo '<script>alert("Mascota Registrada."); window.location.href = "/test/test.php";</script>';
            } else {
                $errorQuery = "Error al insertar registro: " . $conn->error . "</br>Si el error persiste informa al departamento de sistemas, lamentamos las molestias.";
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            $errorQuery = "Error en la preparación de la consulta: " . $conn->error . "</br>Informa al departamento de sistemas, lamentamos las molestias.";
        }
        }
    // Cerrar la conexión a la base de datos
    mysqli_close($conn);
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
    <link rel="stylesheet" href="/css/petRegister.css" />
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
                <li><a href="/views/ts/tSocial.php">Inicio</a></li>
                <li><a href="#" id="killSession">Salir</a></li>
            </ul>
        </nav>
        <div class="section__container header__container" id="home">
            <p style="color: black">UPA Valle de Chalco Solidaridad</p>
            <h1><span>Generar Acta</span></h1>
        </div>
    </header>

    <!-- Ingresar registro aqui abajo -->
    <section class="login" style="padding-bottom: 150px; padding-top: 100px">
        <div class="form-box">
            <div class="form-value">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <div class="twoColumns">
                        <!-- !DATOS DE LA MASCOTA -->
                        <div class="petData">
                            <h2 class="form" style="color: #666c6c;">Mascota</h2>
                            <hr />

                            <div class="inputbox">
                                <input type="text" name="petName" id="petName" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,25}"
                                    required title="Solo letras y espacios" maxlength="25" />
                                <label>Nombre Mascota</label>
                            </div>

                            <div class="inputbox">
                                <input type="text" name="petBreed" id="petBreed" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,25}"
                                    required title="Solo letras y espacios" maxlength="25" />
                                <label>Raza</label>
                            </div>

                            <div class="inputbox">
                                <input type="text" name="petColor" id="petColor" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,25}"
                                    required title="Solo letras y espacios" maxlength="25" />
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
                                    pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]{3,25}" required title="Solo letras y espacios"
                                    maxlength="25" />
                                <label>Nombre Propietario</label>
                            </div>

                            <div class="inputbox">
                                <input type="text" name="ownerINE" id="ownerINE" required maxlength="25" />
                                <label>INE</label>
                            </div>

                            <div class="inputbox">
                                <input type="text" name="ownerCURP" id="ownerCURP" required maxlength="18"
                                    minlength="18" required title="El CURP se compone de 18 caracteres" />
                                <label>CURP</label>
                            </div>

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
                                <input type="text" name="ownerAddress" id="ownerAddress" required maxlength="254" />
                                <label>Domicilio</label>
                            </div>
                        </div>
                    </div>
                    <!--! Fin del div "Two Columns"  -->
                    <script src="/javascript/validarComboBoxPets.js"></script>
                    <div style="text-align: center;"><div><button class="login" style="width: 240px;" onclick="return validarComboBox()">Registrar Mascota</button></div></div>
                    <div class="returnError"><span id="errorMsg"></span></div>
                    <div class="returnSuccesful"><span><?php echo $successfulQuery; ?></span></div>
                </form>
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
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="/javascript/indexAnimations.js"></script>
    <script src="/javascript/sessionTools.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>