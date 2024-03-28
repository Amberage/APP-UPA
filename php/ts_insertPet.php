<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
// Variables de control de errores
$tamanoMaximo = 20 * 1024 * 1024; // Tamaño máximo permitido en bytes (20MB)
$esValido = false;
$responseData = [];
$responseData['successfulMssg'] = '';
$responseData['errorMsg'] = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos del TS
    $idTS = strtoupper($_POST['idTS']);
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
    $queryFolio = "SELECT COUNT(*) AS lastFolio FROM backup_mascotasPropietarios;";
    $lastFolio = $conn->query($queryFolio);

    if ($lastFolio) {
        // Obtener el resultado como un array asociativo y Obtener el último folio
        $row = $lastFolio->fetch_assoc();
        $lastFolio = $row['lastFolio'];
        $folioActual= strval($lastFolio + 1);
        $responseData['folio'] = $folioActual;
        $esValido = true;
    } else {
        // Manejar el error si la consulta falla
        $lastFolio = -1; 
        $esValido = false;
        $responseData['errorMsg'] = "Error en la consulta del ultimo foloio: " . "</br>Informa al departamento de sistemas, lamentamos las molestias.";
    }

    //! Validar la imagen
    if ($esValido == true && isset($_FILES['petPicture']) && $_FILES['petPicture']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES["petPicture"]["size"] <= $tamanoMaximo) {
    
            // Ruta donde se guardará la imagen
            $path_petPictures = $_SERVER['DOCUMENT_ROOT'] . '/assets/petPictures/';
    
            // Nombre del archivo
            $fileName = $folioActual;
    
            // Ruta completa del archivo
            $savedPath = $path_petPictures . $fileName . ".jpg";
            $savedPathBackup = $path_petPictures . "/backup/" . $fileName . ".jpg";
            $petPicture = str_replace($pathPicturesReplace, $pathChars, $savedPath);
    
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
                        $responseData['errorMsg'] = "Formato de imagen no soportado.";
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
                $newImg = imagecreatetruecolor(350, 350);
                imagecopyresampled($newImg, $oldImg, 0, 0, 0, 0, 350, 350, imagesx($oldImg), imagesy($oldImg));
    
                // Ruta donde se encuentra el marco PNG
                $pathFrame = ($_SERVER['DOCUMENT_ROOT'] . '/assets/images/frameHeart.png');
    
                // Cargamos la imagen del marco
                $frame = imagecreatefrompng($pathFrame);
    
                // Obtener las dimensiones de la imagen reducida
                $width = imagesx($newImg);
                $height = imagesy($newImg);
    
                // Obtener las dimensiones del marco
                $frameWidth = imagesx($frame);
                $frameHeight = imagesy($frame);
    
                // Calcular la posición para centrar el marco
                $x = ($width - $frameWidth) / 2;
                $y = ($height - $frameHeight) / 2;
    
                // Superponer el marco sobre la imagen reducida
                imagecopy($newImg, $frame, $x, $y, 0, 0, $frameWidth, $frameHeight);
    
                // Guardamos la imagen resultante
                imagejpeg($newImg, $savedPath);
                imagejpeg($newImg, $savedPathBackup);
    
                // Liberamos memoria
                imagedestroy($newImg);
                imagedestroy($oldImg);
                imagedestroy($frame);
    
                //echo "La imagen se ha subido correctamente.";
                $esValido = true;
    
            } else {
                $responseData['errorMsg'] = "Formato de imagen no válido. Solo se permiten formatos JPEG y PNG.";
                $esValido = false;
            }
    
        } else {
            $responseData['errorMsg'] = "El tamaño del archivo excede el límite permitido (20MB).";
            $esValido = false;
        }
    } else {
        $esValido == false;
        $responseData['errorMsg'] = 'Error en la recepción de la fotografia, informe al departamento de sistemas';
    }
    

    if($esValido == true) {
        // Query para insertar datos en la tabla mascotasPropietarios
        $query = "INSERT INTO mascotasPropietarios (petName, petBreed, petColor, petSex, petPicture, ownerName, ownerINE, ownerCURP, ownerColony, ownerAddress, idTS) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        if ($stmt = $conn->prepare($query)) {
            // Vincular variables a la declaración preparada como parámetros
            $stmt->bind_param("ssssssssssi", $petName, $petBreed, $petColor, $petSex, $petPicture, $ownerName, $ownerINE, $ownerCURP, $ownerColony, $ownerAddress, $idTS);

            // Ejecutar la declaración para generar un word
            if ($stmt->execute()) {
                $responseData['successfulMssg'] = "El acta de " . $petName . " fue generada.";
            } else {
                $responseData['errorMsg'] = "Error al insertar registro: " . $conn->error . "</br>Si el error persiste informa al departamento de sistemas, lamentamos las molestias.";
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            $responseData['errorMsg'] ="Error en la preparación de la consulta: " . $conn->error . "</br>Informa al departamento de sistemas, lamentamos las molestias.";
        }
        }
    // Cerrar la conexión a la base de datos
    mysqli_close($conn);
    echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
    }
?>