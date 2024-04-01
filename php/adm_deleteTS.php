<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idUPA = 3;
    $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $idTS = $conn->real_escape_string($_POST['idTS']);
    $response = "";
    $queryResult = false;

    $sqlName = "SELECT CONCAT(nombre, ' ', apellido) AS nombre_completo FROM usuarios WHERE id = 3";
    $stmt = $conn->prepare($sqlName);
    $stmt->bind_param("i", $idTS);
    $stmt->execute();
    $stmt->bind_result($upaName);
    $stmt->fetch();
    $stmt->close();

    // Paso 1: Verificar existencia de actas
    $folios = array();
    $sql = "SELECT folio FROM mascotasPropietarios WHERE idTS = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idTS);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $folios[] = $row['folio'];
        }
    }

    $stmt->close();

    // Paso 2: Verificar si el arreglo tiene información
    if (!empty($folios)) {
        // Respaldar las actas en la cuenta del admin
        $backupActas = "UPDATE mascotasPropietarios SET idTS = ? WHERE idTS = ?";
        $stmt = $conn->prepare($backupActas);
        $stmt->bind_param("ss", $idUPA ,$idTS);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $queryResult = true;
            $response = "Actas respaldadas";
            $esValido = true;
        } else {
            $queryResult = false;
            $response = "No se han podido respaldar las actas, por lo tanto no se ha eliminado al usuario.";
            $esValido = false;
        }

        $stmt->close();

        if($esValido == true) {
            // Eliminar a TS cuando SI tiene actas.
            $path_petPictures = $_SERVER['DOCUMENT_ROOT'] . '/assets/petPictures/';
            foreach ($folios as $folio) {
                /*$petPicture = $path_petPictures . $folio . ".jpg";
                if (file_exists($petPicture)) {
                    unlink($petPicture); 
                }*/
                //pass: Muy tarde me di cuenta que eliminar al usuario no implica eliminar a sus actas pues estas solo tienen que cambiar de dueño, que asi funcione en MySQL no implica que deba ser asi en la práctica.
                //!Asi es como acabo de perder 6 horas en una función que no era necesaria xd
            }

            $delUser = "DELETE FROM usuarios WHERE id = ?";
            $stmt = $conn->prepare($delUser);
            $stmt->bind_param("s", $idTS);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $queryResult = true;
                $response = "El usuario ha sido eliminado, " . count($folios) . " actas que tenía registradas ahora son propiedad de $upaName";
            } else {
                $queryResult = false;
                $response = "Error al eliminar el usuario con el idTS: " . $idTS . ". No se encontraron actas asociadas al usuario.";
            }

            $stmt->close();
        }
    } else {
        // Eliminar a TS cuando no tiene actas.
        $delUser = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($delUser);
        $stmt->bind_param("s", $idTS);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $queryResult = true;
            $response = "El usuario no tenia actas registradas, por lo tanto las actas no se han visto modificadas.";
        } else {
            $queryResult = false;
            $response = "Error al eliminar el usuario con el idTS: " . $idTS . ". El usuario no tenía actas registradas.";
        }

        $stmt->close();
    }

    $conn->close();
    
    // Preparar la respuesta como un array JSON
    $result = array(
        "queryResult" => $queryResult,
        "message" => $response
    );

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>