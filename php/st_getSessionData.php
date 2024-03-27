<?php
session_start();
$sessionData = [];

if (!isset($_SESSION["id"]) || !isset($_SESSION["nombre"]) || !isset($_SESSION["apellido"]) || !isset($_SESSION["userType"])) {
    $sessionData['id'] = 'null';
    $sessionData['nombre'] = 'null';
    $sessionData['apellido'] = 'null';
    $sessionData['userType'] = 'null';
} else {
    $sessionData['id'] = $_SESSION["id"];
    $sessionData['nombre'] = $_SESSION["nombre"];
    $sessionData['apellido'] = $_SESSION["apellido"];
    $sessionData['userType'] = $_SESSION["userType"];
}

echo json_encode($sessionData, JSON_UNESCAPED_UNICODE);
?>