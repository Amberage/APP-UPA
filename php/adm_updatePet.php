<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
// Variables de control de errores
$tamanoMaximo = 20 * 1024 * 1024; // Tamaño máximo permitido en bytes (20MB)
$esValido = false;
$responseData = [];
$responseData['successfulMssg'] = '';
$responseData['errorMsg'] = '';
$responseData['resultQuery'] = 0;

function modifyBackupPath($folioBP, $pathPictureBP) {
    require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
    $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida en modifyBackupPath: " . $conn->connect_error);
    }
    // Preparar la declaración SQL y vincular parámetros
    $updateBackup = $conn->prepare("UPDATE backup_mascotasPropietarios SET petPicture = ? WHERE folio = ?");
    $updateBackup->bind_param("si", $pathPictureBP, $folioBP);

    // Ejecutar la declaración
    if ($updateBackup->execute() === TRUE) {
        $conn->close();
        return true;
    } else {
        $conn->close();
        return false;
    } 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $folioActa = $_POST['folioActa'];
    $old_petPicture = $_POST['old_petPicture'];
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
    $idTS = $_POST['dataTS'];

    if(isset($_FILES['petPicture']) && $_FILES['petPicture']['error'] === UPLOAD_ERR_OK) {
        //! Validar la imagen: Aqui se genera la nueva imagen y se guarda en la ruta adecuada.
        if ($_FILES["petPicture"]["size"] <= $tamanoMaximo) {
            // Ruta donde se guardará la imagen
            $path_petPictures = $_SERVER['DOCUMENT_ROOT'] . '/assets/petPictures/';
    
            // Nombre del archivo
            $fileName = $folioActa;
    
            // Ruta completa del archivo
            $savedPath = $path_petPictures . $fileName . ".jpg";
            $savedPathBackup = $path_petPictures . "backup/" . $fileName . ".jpg";
            $petPicture = str_replace($pathPicturesReplace, $pathChars, $savedPath);
            $petPictureBackup = str_replace($pathPicturesReplace, $pathChars, $savedPathBackup);

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
    
                $esValido = true;
                $imagenActualizada = true;
            } else {
                $responseData['errorMsg'] = "Formato de imagen no válido. Solo se permiten formatos JPEG y PNG.";
                $esValido = false;
            }
    
        } else {
            $responseData['errorMsg'] = "El tamaño del archivo excede el límite permitido (20MB).";
            $esValido = false;
        }
        $esValido = true;
    } else {
        $petPicture = $old_petPicture;
        $esValido = true;
        $imagenActualizada = false;
    }

    // Iniciar la conexión a la BBDD
    $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);

    if($esValido == true) {
        // Query para insertar datos en la tabla mascotasPropietarios
        $query = "UPDATE mascotasPropietarios SET ";
        $query .= "petName = ?, ";
        $query .= "petBreed = ?, ";
        $query .= "petColor = ?, ";
        $query .= "petSex = ?, ";
        $query .= "petPicture = ?, ";
        $query .= "ownerName = ?, ";
        $query .= "ownerINE = ?, ";
        $query .= "ownerCURP = ?, ";
        $query .= "ownerColony = ?, ";
        $query .= "ownerAddress = ?, ";
        $query .= "idTS = ? ";
        $query .= "WHERE folio = ?";

        // Preparar la consulta
        if ($stmt = $conn->prepare($query)) {
            // Vincular variables a la declaración preparada como parámetros
            $stmt->bind_param("sssssssssssi", $petName, $petBreed, $petColor, $petSex, $petPicture, $ownerName, $ownerINE, $ownerCURP, $ownerColony, $ownerAddress, $idTS, $folioActa);

            // Ejecutar la declaración para generar un word
            if ($stmt->execute()) {
                $responseData['successfulMssg'] = "¡El acta de " . $petName . " ha sido actualizada!";
                $responseData['resultQuery'] = true;
                if($imagenActualizada == true) {
                    $responseData['backupRequired'] = 'si';
                    $backupAdd = modifyBackupPath($folioActa, $petPictureBackup);
                    if($backupAdd == true) {
                        $responseData['backupState'] = true;
                    } else {
                        $responseData['backupState'] = false;
                    }
                } else {
                    $responseData['backupRequired'] = 'no';
                    $responseData['backupState'] = false;
                }
            } else {
                $responseData['errorMsg'] ="Error al insertar registro: " . $conn->error . "</br>Si el error persiste informa al departamento de sistemas, lamentamos las molestias.";
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            $responseData['errorMsg'] ="Error en la preparación de la consulta: " . $conn->error . "</br>Informa al departamento de sistemas, lamentamos las molestias.";
        }
        }
    // Cerrar la conexión a la base de datos
    mysqli_close($conn);
    $responseData['validateQuery'] = $esValido;
    echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
    }
?>