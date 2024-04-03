<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
  $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);
  $userFolio = $_POST["userFolio"];
  $userINE = $_POST["userINE"];

  // Construir la consulta SQL
  $sqlSearch = "SELECT folio FROM mascotasPropietarios WHERE folio = '$userFolio' AND ownerINE = '$userINE'";
  
  // Ejecutar la consulta
  $result = $conn->query($sqlSearch);

  // Verificar si se encontraron resultados
  if ($result->num_rows > 0) {
    $responseData = array(
      "status" => "success",
      "message" => "El folio y el INE ya existen en la base de datos",
      "folio" => $userFolio,
      "resultQuery" => true
    );
  } else {
    $responseData = array(
      "status" => "success",
      "message" => "El folio y el INE no existen en la base de datos",
      "resultQuery" => false
    );
  }

  // Cerrar la conexión
  $conn->close();

  // Enviar la respuesta JSON
  echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
}
?>
