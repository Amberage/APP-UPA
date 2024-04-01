<?php
/*
* Este script pasa la información de la BBDD al HTML mediante el loader.js
* Busqueda dinámica (Filtros): https://www.youtube.com/watch?v=IP2Ye2KKfoc&t
* Limit(00:00) y Paginación(10:28): https://www.youtube.com/watch?v=NHF7RH3ALPM&t
*/
require ($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
$conn = new mysqli($servername, $mysql_username, $mysql_password, $dbname);
// Datos de la tabla
$primaryKey = 'id';
$table = 'usuarios';
$columns = ['nombre', 'apellido', 'username', 'userType', 'id'];

// Filtro
$searchData = isset($_POST['searchData']) ? $conn -> real_escape_string($_POST['searchData']) : null;
$statementAND = '';
if ($searchData != null) {
    $statementAND = 'AND (';

    $cont = count($columns);
    for ($i = 0; $i < $cont - 2; $i++) { //-1 Para omitir el filtro por id y usertype
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
WHERE userType != '0' $statementAND $stamentLIMIT";
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
        if($row['id'] > 3) {
            $buttons = "<button class='btnAction' style='margin-right: 3px; background-image: url(\"/assets/images/editarActa.png\");' onClick=editTS(" . $row['id'] . ");></button>" .
            "<button class='btnAction' style='background-image: url(\"/assets/images/eliminarActa.png\");' onClick=deleteTS(" . $row['id'] . ");></button>" .
            '</td>';
        }  else {
            $buttons = "<button class='btnAction' style='margin-right: 3px; background-image: url(\"/assets/images/editarActa.png\");' onClick=editTS(" . $row['id'] . ");></button>" .
            '</td>';
        }
        $data['table'] .= '<tr>';
        $data['table'] .= '<td><label class="lbl-info">Nombre: </label><span id="name' . $row['id'] . '">' . $row['nombre'] . '</span></td>';
        $data['table'] .= '<td><label class="lbl-info">Apellido: </label>' . $row['apellido'] . '</td>';
        $data['table'] .= '<td><label class="lbl-info">Propietario: </label>' . $row['username'] . '</td>';
        $data['table'] .= '<td><label class="lbl-info">userType: </label>' . $row['userType'] . '</td>';
        $data['table'] .= '<td>' . $buttons;
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
$data['debug'] = $sql;
echo json_encode($data, JSON_UNESCAPED_UNICODE);
$conn->close();
?>