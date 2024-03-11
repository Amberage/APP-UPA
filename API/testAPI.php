<?php
/*
* Nota del programador: Llamar a esto API es muy exagerado, pero me hacia ilusión jaja salu2.
* Ahora bien, aqui es donde se genera el pdf que verá el cliente... si no quieren que la app crashee intenten tocar lo menos de esta carpeta.
*/
$folio = 1659;
$folioActual = str_pad($folio, 6, "0", STR_PAD_LEFT);;
$petName = 'CHELSEARIES';
$petSex = 'HEMRBA';
$petBreed = 'MCKENZIE BACKER';
$petColor = 'NEGRO CON BLANCO';
$petPicture = 'background.png';
$ownerName = 'DANIEL ABRAHAM OREGON HERNANDEZ';
$ownerCURP = 'GAVK080529MMCLGNA0';
$ownerINE = 'GAVK080529MMCLGNA0';
$ownerColony = 'SAN ISIDRO';
$ownerAddress = 'DIVISIÓN DEL NORTE LT. 39, MZ. 495, COLONIA SAN ISIDRO, VALLE DE CHALCO ESTADO DE MÉXICO';
$tsName = 'ARANTZA MARITZEL OCAMPO MONTOYA';
$dia = '29';
$month = 'JUNIO';
$anio = '2004';


// Generar la página HTML
echo "<html><body><script src='generatePDF.js'></script>";
echo "<script>createPDF('$folioActual', '$petName', '$petSex', '$petBreed', '$petColor', '$petPicture', '$ownerName', '$ownerCURP', '$ownerINE', '$ownerColony', '$ownerAddress', '$tsName', '$dia', '$month', '$anio');</script>";
echo "</body></html>";
?>
