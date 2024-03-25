<?php
/*
* Este script pasa la información de la BBDD al HTML mediante el loader.js
* Busqueda dinámica (Filtros): https://www.youtube.com/watch?v=IP2Ye2KKfoc&t
* Limit(00:00) y Paginación(10:28): https://www.youtube.com/watch?v=NHF7RH3ALPM&t
*/
$tsID = 1; // Jalarlo desde la sesión
require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
$conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);
// Datos de la tabla
$primaryKey = 'folio';
$table = 'pets';
$columns = ['folio', 'petName', 'ownerName', 'ownerColony', 'registerDate', 'petPicture'];

// Filtro
$searchData = isset($_POST['searchData']) ? $conn -> real_escape_string($_POST['searchData']) : null;
$statementAND = '';
if ($searchData != null) {
    $statementAND = 'AND (';

    $cont = count($columns);
    for ($i = 0; $i < $cont - 1; $i++) { // - 1 para quitar del filtro el petPicture
        $statementAND .= $columns[$i] . " LIKE '%" . $searchData . "%' OR ";
    }
    $statementAND = substr_replace($statementAND, "", -3);
    $statementAND .= ")";
}

// LIMIT y paginación
$limit = isset($_POST['numRegisters']) ? $conn -> real_escape_string($_POST['numRegisters']) : 10;
$actualPage = isset($_POST['actualPage']) ? $conn -> real_escape_string($_POST['actualPage']) : 0;
if(!$actualPage) {
    $inicio = 0;
    $actualPage = 1;
} else {
    $inicio = ($actualPage - 1) * $limit;
}
$stamentLIMIT = "LIMIT $inicio, $limit";

//Query General
$sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
FROM $table
WHERE tsID = $tsID $statementAND $stamentLIMIT";
$result = $conn -> query($sql);
$num_rows = $result -> num_rows;

// Query para total de registros filtrados
$sqlFiltro = "SELECT FOUND_ROWS()";
$resultFiltro = $conn -> query($sqlFiltro);
$rowFiltro = $resultFiltro -> fetch_array();
$totalFiltro = $rowFiltro[0];

$sqlTotal = "SELECT count($primaryKey) FROM $table";
$resultTotal = $conn -> query($sqlTotal);
$rowTotal = $resultTotal -> fetch_array();
$totalRegistros = $rowTotal[0];

/*
* El siguiente arreglo 'data[]' almacena información y la recibe el js para acceder a ella de la forma data.id
*/
$data = [];
$data['totalRegistros'] = $totalRegistros;
$data['totalFiltro'] = $totalFiltro;
$data['table'] = '';
$data['paginacion'] = '';

// Mandar al HTML
if ($num_rows > 0) {
    $data['table'] = '';
    while ($row = $result -> fetch_assoc()) {       
        $data['table'] .= '<tr>';
        $data['table'] .= '<td><label class="lbl-info">No. Acta: </label>' . $row['folio'] . '</td>';
        $data['table'] .= '<td><label class="lbl-info">Nombre: </label>' . $row['petName'] . '</td>';
        $data['table'] .= "<td><img style='text-align: center;' src='" . $row['petPicture'] . "' class='actasImg'></img></td>";
        $data['table'] .= '<td><label class="lbl-info">Propietario: </label>' . $row['ownerName'] . '</td>';
        $data['table'] .= '<td><label class="lbl-info">Colonia: </label>' . $row['ownerColony'] . '</td>';
        $data['table'] .= '<td><label class="lbl-info">Registrado el </label>' . $row['registerDate'] . '</td>';
        $data['table'] .= '<td>' .
                        "<button class='btnAction' style='margin-right: 3px; background-image: url(\"/assets/images/descargarActa.png\");' onClick=\"printPet(" . $row['folio'] . ")\";></button>" .
                        "<button class='btnAction' style='margin-right: 3px; background-image: url(\"/assets/images/editarActa.png\");' onclick=\"alert('Función en desarrollo, maldito desesperado!');\"></button>" .
                        "<button class='btnAction' style='background-image: url(\"/assets/images/eliminarActa.png\");' onClick=deletePet(". $row['folio'] .");></button>" .
                 '</td>';
        $data['table'] .= '</tr>';
    }
} else {
    $data['table'] = '';
    $data['table'] .= '<tr>';
    $data['table'] .= '<td colspan="7">Sin resultados</td>';
    $data['table'] .= '</tr>';
}

// Calcular paginación
if($totalRegistros > 0) {
    $totalPaginas = ceil($totalRegistros / $limit);

    $data['paginacion'] .= '<nav>';
    $data['paginacion'] .= '<ul>';

    $numeroInicio = 1;
    if(($actualPage - 4) > 1) {
        $numeroInicio = $actualPage - 4;
    }

    $numeroFin = $numeroInicio + 9;

    if($numeroFin > $totalPaginas) {
        $numeroFin = $totalPaginas;
    }

    for($i = $numeroInicio; $i <= $numeroFin; $i++) {
        if ($actualPage == $i) {
            // TODO: Aqui aplicar CSS para que se resalte este campo ya que se encuentra activo
            $data['paginacion'] .= '<li><a class="paginaActual" href="#">' . $i . '</a></li>';
        } else {
            $data['paginacion'] .= '<li><a class="restoPaginas" href="#" onClick="getData(' . $i . ')">' . $i . '</a></li>';
        }
    }

    $data['paginacion'] .= '</ul>';
    $data['paginacion'] .= '</nav>';
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
$conn->close();
?>