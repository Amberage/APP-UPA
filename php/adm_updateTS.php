<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idTS = $_POST['idTS'];
    $nombre = $_POST['tsName'];
    $apellido = $_POST['tsLastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    $esValido = false;
    $responseData = array();

    /* validacion username: 
    * Dado el idTS (primarykey e irrepetible en los registros) y el username, revisar en la tabla de usuarios lo siguiente:
    * Si el username proporcionado no existe en la tabla, regresa $esValido = true, pues no se considera duplicidad.
    * Si el idTS esta asociado a el username proporcionado, regresa $esValido = true, pues no se considera duplicidad.
    * Si existe el username proporcionado pero no se encuentra asociado al idTS, regresa $esValido = false, pues se considera duplicidad.
    * La logica es:
    * if(username existe en la tabla) {
        if(username esta asociado al mismo idTs) {
            $esValido = true; //No existe duplicidad
        } else {
            $esValido = false; //Existe duplicidad
        }
    } else {
        $esValido = true;
    }
    */

    // Validación del username
    $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM usuarios WHERE username = ? AND id != ?");
    $stmt->bind_param("si", $username, $idTS);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    if ($row['count'] == 0) {
        $esValido = true;
    } else {
        $esValido = false;
        $responseData['resultQuery'] = false;
        $responseData['errorMsg'] = "Este nombre de usuario ya se encuentra en uso.";
    }
    mysqli_close($conn);

    //No modificar nada del siguiente codigo...
    if($esValido == true) {
        if($password === '' && $confirmPassword === '') {
            $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);
            $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, username = ? WHERE id = ?");
            $stmt->bind_param("sssi", $nombre, $apellido, $username, $idTS);
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
                $responseData['successMsg'] = "Los datos de " . $nombre . " se actualizaron correctamente.";
                $responseData['resultQuery'] = true;
            } else {
                $responseData['errorMsg'] = "Hubo un problema al actualizar los datos sin la nueva contraseña, favor de comunicarse con el departamento de sistemas." . mysqli_error($conn);
                $responseData['resultQuery'] = false;
            }
    
            $stmt->close();
            mysqli_close($conn);
        } else {
            
            if($password == $confirmPassword) {
                $esValido = true;
            } else {
                $esValido = false;
                $responseData['errorMsg'] = "Las contraseñas no coinciden";
                $responseData['resultQuery'] = false;
            }
    
            if($esValido == true) {
                $conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, username = ?, password = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $nombre, $apellido, $username, $hashedPassword, $idTS);
                $stmt->execute();
    
                if ($stmt->affected_rows > 0) {
                    $responseData['successMsg'] = "Los datos de " . $nombre . " se actualizaron correctamente.";
                    $responseData['resultQuery'] = true;
                } else {
                    $responseData['errorMsg'] = "Hubo un problema al actualizar los datos con la nueva contraseña, favor de comunicarse con el departamento de sistemas.";
                    $responseData['resultQuery'] = false;
                }
                $stmt->close();
                mysqli_close($conn);
            }
        } 
    }
    echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
}
?>
