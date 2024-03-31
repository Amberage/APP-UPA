<?php
session_start();
$sessionData = [];

if (!isset($_SESSION["id"]) || !isset($_SESSION["nombre"]) || !isset($_SESSION["apellido"]) || !isset($_SESSION["userType"])) {
    $sessionData['id'] = 'null';
    $sessionData['nombre'] = 'null';
    $sessionData['apellido'] = 'null';
    $sessionData['userType'] = 'null';
    $sessionData['all_tsIDs'] = 'null';
} else {
    $sessionData['id'] = $_SESSION["id"];
    $sessionData['nombre'] = $_SESSION["nombre"];
    $sessionData['apellido'] = $_SESSION["apellido"];
    $sessionData['userType'] = $_SESSION["userType"];
    $sessionData['all_tsIDs'] = getUsersID();
}

function getUsersID() {
    require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
    $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);
    // Almacenar los IDs de usuarios en una variable de sesión
    $sql_ids = "SELECT id FROM usuarios WHERE usertype='ts' OR id = 3";
    $result_ids = $conn->query($sql_ids);
    $ids = array();
    if ($result_ids->num_rows > 0) {
        while($row_ids = $result_ids->fetch_assoc()) {
            $ids[] = $row_ids["id"];
        }
        return $ids;
    } else {
        return $ids[] = array();
    }
    $conn->close();
}

echo json_encode($sessionData, JSON_UNESCAPED_UNICODE);
?>