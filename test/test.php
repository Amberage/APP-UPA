<?php
// Iniciar sesión si aún no está iniciada
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <!-- Imprimir las variables de sesión dentro del cuerpo HTML -->
        <p>ID: <?php echo $_SESSION["id"]; ?></p>
        <p>Nombre: <?php echo $_SESSION["nombre"]; ?></p>
        <p>Apellido: <?php echo $_SESSION["apellido"]; ?></p>
        <p>Tipo de usuario: <?php echo $_SESSION["userType"]; ?></p>
    </div>
</body>
</html>
