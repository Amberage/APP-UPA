<?php
use \PhpOffice\PhpWord\Settings;
use \PhpOffice\PhpWord\Style\Language;
use \Dompdf\Dompdf;
use \Dompdf\Options;

function generarWord($folio) {
    require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
    $connection = new mysqli($servername, $mysql_username, $mysql_password, $dbname);

    // Verifica la conexión
    if ($connection->connect_error) {
        die("La conexión falló: " . $connection->connect_error);
    }
    
    // Crear la consulta SQL SELECT con JOIN para obtener el nombre y apellido del usuario relacionado
    $sql = "SELECT mp.petName, mp.petSex, mp.petBreed, mp.petColor, mp.petPicture, mp.ownerName, mp.ownerCURP, mp.ownerINE, mp.ownerColony, mp.ownerAddress, CONCAT(u.nombre, ' ', u.apellido) AS nombreCompleto, DAY(mp.fechaRegistro) as dia, MONTH(mp.fechaRegistro) as mes, YEAR(mp.fechaRegistro) as anio
            FROM mascotasPropietarios mp
            INNER JOIN usuarios u ON mp.idTS = u.id
            WHERE mp.folio = $folio";
    
    // Ejecutar la consulta
    $result = $connection->query($sql);
    
    // Verificar si hay resultados y almacenarlos en variables si es así
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $petName = $row["petName"];
            $petSex = $row["petSex"];
            $petBreed = $row["petBreed"];
            $petColor = $row["petColor"];
            $petPicture = $row["petPicture"];
            $ownerName = $row["ownerName"];
            $ownerCURP = $row["ownerCURP"];
            $ownerINE = $row["ownerINE"];
            $ownerColony = $row["ownerColony"];
            $ownerAddress = $row["ownerAddress"];
            $tsName = $row["nombreCompleto"];
            $dia = (string)$row["dia"];
            $month = $row["mes"];
            $anio = (string)$row["anio"];

            $meses = [
                1 => "ENERO",
                2 => "FEBRERO",
                3 => "MARZO",
                4 => "ABRIL",
                5 => "MAYO",
                6 => "JUNIO",
                7 => "JULIO",
                8 => "AGOSTO",
                9 => "SEPTIEMBRE",
                10 => "OCTUMBRE",
                11 => "NOVIEMBRE",
                12 => "DICIEMBRE"
            ];

            $mes = isset($meses[$month]) ? $meses[$month] : "Mes inválido";
        }

        //Generar PDF
        require_once ($_SERVER['DOCUMENT_ROOT'] . '/libraries/vendor/autoload.php');
        $pathTemplate = ($_SERVER['DOCUMENT_ROOT'] . '/assets/templates/upa.docx');
        $pathPetPicture = ($_SERVER['DOCUMENT_ROOT'] . '/actas/petPictures/' . $folio . ".jpg");
        $pathWordFile = ($_SERVER['DOCUMENT_ROOT'] . '/actas/' . $folio . ".docx");
        $pathPDFFile = ($_SERVER['DOCUMENT_ROOT'] . '/actas/' . $folio . ".pdf");

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($pathTemplate);

        // Establecer los valores
        $templateProcessor->setValues([
            /* 'texto en el Word' => 'valor que se remplazará' */
            //Datos mascota
            'petID' => $folio,
            'petName' => $petName,
            'petSex' => $petSex,
            'petBreed' => $petBreed,
            'petColor' => $petColor,
            //Datos propietario
            'ownerName' => $ownerName,
            'ownerCURP' => $ownerCURP,
            'ownerINE' => $ownerINE,
            'ownerColony' => $ownerColony,
            'ownerAddress' => $ownerAddress,
            //Datos del trabajador social
            'tsName' => strtoupper($tsName),
            'day' => $dia,
            'month' => $mes,
            'year' => $anio
        ]);

        $templateProcessor->setImageValue('petPic', [
            'path' => $pathPetPicture,
            'width' => 165,
            'height' => 165,
            'ratio' => true,
            'borderSize' => 2,
            'borderColor' => '9D2348'
        ]);

        $templateProcessor->saveAs($pathWordFile);
        
        // Guardar el documento de Word
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($pathWordFile) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($pathWordFile));

        // Leer el archivo y enviar su contenido al navegador
        readfile($pathWordFile);

    } else {
        echo "No se encontraron resultados para el folio $folio";
        die("Error catastrofico... el folio que entro fue:" .$folio);
    }
    
    // Cerrar la conexión
    $connection->close();
}
generarWord(3);
?>