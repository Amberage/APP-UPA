<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
$conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);
$queryStatus = false;
if ($conn->connect_error) {
    $responseInfo['errorMsg'] = "Error de conexión al obtener el token de SU: " . $conn->connect_error . "... Revisa el error log del servidor y corrigue el problema.";
    $queryStatus = false;
}
$sqlGetToken = "SELECT token FROM tokenSuperUser";
$result = $conn->query($sqlGetToken);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $token = $row["token"];
    $conn->close();
    $responseInfo['tokenSU'] = $token;
    $queryStatus = true;
} else {
    $responseInfo['errorMsg'] = "No se encontró ningún token en la base de datos, caso no esperado, revisa la base de datos del sistema y corrigue el token.";
    $queryStatus = false;
}
$responseInfo['queryStatus'] = $queryStatus;
echo json_encode($responseInfo, JSON_UNESCAPED_UNICODE);
?>