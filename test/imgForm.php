<?php
// Verificamos el tamaño del archivo

// Verificamos si se ha enviado un archivo al hacer clic en el botón
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]) && isset($_FILES["petPicture"])) {
    //Generar nuevo nombre aqui...
    $nombreArchivoOriginal = $_FILES["petPicture"]["name"];
    $extension = pathinfo($nombreArchivoOriginal, PATHINFO_EXTENSION);
    $nuevoNombre = uniqid() . '.' . $extension;

    // Verificamos el tamaño del archivo
    if ($_FILES["petPicture"]["size"] <= $tamanoMaximo) {

        // Ruta donde se guardará la imagen
        $path_petPictures = $_SERVER['DOCUMENT_ROOT'] . '/actas/petPictures/';

        // Nombre del archivo
        $fileName = 'ejemplo.jpg';

        // Ruta completa del archivo
        $savedPath = $path_petPictures . $fileName;

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
                    echo "Formato de imagen no soportado.";
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

            // Redimensionamos la imagen a 150x150
            $newImg = imagecreatetruecolor(150, 150);
            imagecopyresampled($newImg, $oldImg, 0, 0, 0, 0, 150, 150, imagesx($oldImg), imagesy($oldImg));

            // Guardamos la imagen redimensionada como JPEG
            imagejpeg($newImg, $savedPath);

            // Liberamos memoria
            imagedestroy($newImg);
            imagedestroy($oldImg);

            echo "La imagen se ha subido correctamente.";

        } else {
            echo "Formato de imagen no válido. Solo se permiten formatos JPEG y PNG.";
        }

    } else {
        echo "El tamaño del archivo excede el límite permitido (20MB).";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="inputbox" style="position: relative;">
            <input class="fileBox" type="file" name="petPicture" id="petPicture" accept="image/*" required/>
            <label style="top: 10px;">Foto</label>
        </div>
        <button type="submit" name="submit">Subir imagen</button>
    </form>
</body>
</html>
