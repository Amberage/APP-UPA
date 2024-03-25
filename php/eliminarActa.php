<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
$conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);
$response = '';
// Filtro
$delFolio = $conn -> real_escape_string($_POST['delFolio']);

//Query General
$query = "DELETE FROM mascotasPropietarios WHERE folio = '$delFolio'";

// Ejecutar la consulta
if ($conn->query($query) === TRUE) {
    $response = "El acta " . $delFolio . " ha sido eliminada!";
} else {
    $response = "Error al eliminar el acta: " . $delFolio . " Error: " . $conn->error . "</br> Favor de comunicarse con el departamento de sistemas";
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
$conn->close();
?>
