<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $confirmToken = $_POST['confirmToken'];
    if($confirmToken === 'kmpgySaEjlidYi4G7t612zZY1QqZXIpWw2A5hboa1zd91ehlGQWVU94zd34UeH3f') {
        require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
        $delPath = ($_SERVER['DOCUMENT_ROOT'] . '/assets/petPictures');
        $delBP = ($_SERVER['DOCUMENT_ROOT'] . '/assets/petPictures/backup');
        $responseData = [];
        $log = ''; // Inicializar la variable $log
        
        $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);
        // Verificar conexión
        if ($conn->connect_error) {
            $responseData['errorMsg'] = "La conexión falló: " . $conn->connect_error . ", soluciona el error.";
            $queryStatus = false;
            $responseData['queryStatus'] = $queryStatus;
            echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
            die("La conexión falló: " . $conn->connect_error);
        } else {
            $queryStatus = true;
            $responseData['queryStatus'] = $queryStatus;
        }
        
        // TRUNCATE TABLE mascotasPropietarios;
        $log .= "*** REGISTRO TABLA mascotasPropietarios ***\n";
        $sql_truncate = "TRUNCATE TABLE mascotasPropietarios";
        if ($conn->query($sql_truncate) === TRUE) {
            $log .= "Tabla mascotasPropietarios truncada con éxito\n";
        } else {
        $log .= "Error al truncar la tabla mascotasPropietarios: " . $conn->error . "\n";
        }

        // ALTER TABLE mascotasPropietarios AUTO_INCREMENT = 1;
        $sql_alter = "ALTER TABLE mascotasPropietarios AUTO_INCREMENT = 1";
        if ($conn->query($sql_alter) === TRUE) {
            $log .= "AUTO_INCREMENT de mascotasPropietarios reiniciado con éxito\n";
        } else {
            $log .= "Error al reiniciar el AUTO_INCREMENT de mascotasPropietarios la tabla: " . $conn->error . "\n";
        }

        // Eliminar todos los archivos existentes dentro de la ruta $delPath
        $files = glob($delPath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                if (unlink($file)) {
                    $file = str_replace($pathPicturesReplace, $pathChars, $file);
                    $log .= "   - Imagen $file eliminada.\n";
                } else {
                    $file = str_replace($pathPicturesReplace, $pathChars, $file);
                    $log .= "   - Error al eliminar imagen: $file.\n";
                }
            }
        }

        if(!strpos($log, 'Error')) {
            $responseData['mP'] = '¡Las actas han sido eliminadas!';
            $log .= "¡Las actas han sido eliminadas!\n";
        } else {
            $responseData['mP'] = 'Hubo un error al eliminar las actas, reinicia manualmente.';
            $log .= 'Hubo un error al eliminar las actas, reinicia manualmente.\n';
        }

        // TRUNCATE TABLE backup_mascotasPropietarios;
        $log .= "\n\n\n*** REGISTRO TABLA backup_mascotasPropietarios ***\n";
        $sql_truncate = "TRUNCATE TABLE backup_mascotasPropietarios";
        if ($conn->query($sql_truncate) === TRUE) {
            $log .= "Tabla backup_mascotasPropietarios truncada con éxito\n";
        } else {
            $log .= "Error al truncar la tabla backup_mascotasPropietarios: " . $conn->error . "\n";
        }

        // Eliminar todos los archivos existentes dentro de la ruta $delBP
        $files = glob($delBP . '/*'); // Obtener lista de archivos en el directorio
        foreach ($files as $file) {
            if (is_file($file)) {
                if (unlink($file)) {
                    $file = str_replace($pathPicturesReplace, $pathChars, $file);
                    $log .= "   - Imagen backup $file eliminada.\n";
                } else {
                    $file = str_replace($pathPicturesReplace, $pathChars, $file);
                    $log .= "   - Error al eliminar el backup de la imagen: $file.\n";
                }
            }
        }

        if(!strpos($log, 'Error')) {
            $responseData['b_mP'] = '¡Las actas del backup han sido eliminadas!';
            $log .= "¡Las actas del backup han sido eliminadas!\n";
        } else {
            $responseData['b_mP'] = 'Hubo un error al eliminar las actas del backup, reinicia manualmente.';
            $log .= 'Hubo un error al eliminar las actas del backup, reinicia manualmente.\n';
        }

        // Eliminar usuarios
        $log .= "\n\n\n*** REGISTRO TABLA usuarios ***\n";
        $sql_delUser = "DELETE FROM usuarios WHERE id > 3";
        if ($conn->query($sql_delUser) === TRUE) {
            $responseData['users'] = '¡Usuarios eliminados con éxito!';
            $log .= "¡Usuarios eliminados con éxito!\n";
        } else {
            $responseData['users'] = "Error al eliminar usuarios: " . $conn->error;
            $log .= "Error al eliminar usuarios: " . $conn->error . "\n";
        }

        $fechaLogin = getFecha();
        $log .= "\n\n\n" . $fechaLogin;
        $conn->close();
        echo json_encode($responseData, JSON_UNESCAPED_UNICODE);

        // Ruta donde quieres guardar el archivo .txt
        $pathLog = $_SERVER['DOCUMENT_ROOT'] . '/views/sudo/log';
        // Escribe el valor de la variable en el archivo
        file_put_contents($pathLog, $log);
    } else {
        die("Solicitud no esperada");
    }
}

// Definir la función para obtener el mensaje de fecha y hora del proceso
function getFecha() {
    // Establecer la zona horaria
    date_default_timezone_set('America/Mexico_City');

    // Obtener la fecha y hora actual
    $fecha_actual = date('d/m/Y H:i');

    // Obtener componentes de la fecha y hora actual
    $dia = date('d');
    $mes = date('m');
    $año = date('Y');
    $hora = date('H');
    $minuto = date('i');

    // Traducir el número de mes a su nombre correspondiente
    $nombre_mes = '';
    switch ($mes) {
        case '01':
            $nombre_mes = 'enero';
            break;
        case '02':
            $nombre_mes = 'febrero';
            break;
        case '03':
            $nombre_mes = 'marzo';
            break;
        case '04':
            $nombre_mes = 'abril';
            break;
        case '05':
            $nombre_mes = 'mayo';
            break;
        case '06':
            $nombre_mes = 'junio';
            break;
        case '07':
            $nombre_mes = 'julio';
            break;
        case '08':
            $nombre_mes = 'agosto';
            break;
        case '09':
            $nombre_mes = 'septiembre';
            break;
        case '10':
            $nombre_mes = 'octubre';
            break;
        case '11':
            $nombre_mes = 'noviembre';
            break;
        case '12':
            $nombre_mes = 'diciembre';
            break;
        default:
            $nombre_mes = 'mes desconocido';
    }

    // Construir el mensaje con la fecha y hora del proceso
    $mensaje = "PROCESO FINALIZADO: \n Realizado el día $dia del mes de $nombre_mes del año $año a las $hora:$minuto";

    // Retornar el mensaje
    return $mensaje;
}
?>
