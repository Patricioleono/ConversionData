<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$archivoExcel = 'plantillas/plantilla_legible.xlsx';

function sanitizeTabReturn( $str ){   
    $strLimpio = trim($str);
    $fCleaner = preg_replace( '/[\n\r\t]+/', '-', $strLimpio);
    return preg_replace( '/[\n\r\t]+/', '-', $fCleaner);
}
function debug($data){
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}
function validarExistencia($ruta){
    if(file_exists($ruta)){
        $eliminado = unlink($ruta);
    }
    return $eliminado;
}

$codigoIngreso;
$codigo;
$hora;
$min;
$mes;
$dia;
$anio;
$inicio;
$allInOne;
$horario;
$datosArray = array();
$sector;
$bio;
$entradaSalida;
$claveMarca;
$hrs;
$minu;
$month;
$day;
$year;
$completeDate;
$completeHours;
$columna = 2;
$arrayInsertExcel = array();

$ruta = $_FILES['data']['tmp_name'];
$archivo = file($ruta, FILE_IGNORE_NEW_LINES);

//contenido devuelto en un array, es solo una linea de texto por cada linea
for($i = 0; $i < count($archivo); $i++){
    $paraSeparar = explode('-', sanitizeTabReturn($archivo[$i]));
    
    $ceros = ",0000000000";
    $cerosSeparados = ",00,00,00,00,00";

    $codigo = $paraSeparar[0];
    $dia = substr($paraSeparar[3], 0, 2);
    $mes = $paraSeparar[2];
    $anio = substr($paraSeparar[1], 2, 4);

    $horario = explode(':', substr($paraSeparar[3], 2, 9));
    $hora = trim($horario[0]);
    $min = $horario[1];

    if($paraSeparar[5] == 0){
        $inicio = "001,01,01";

    }elseif($paraSeparar[5] == 1){
        $inicio = "001,01,03";
    }

    if(strlen($codigo) == 4){
        $codigoIngreso = ",000000".$codigo;

    }elseif(strlen($codigo) == 8){
        $codigoIngreso = ",00".$codigo;
    }
    
    $allInOne = $inicio . $codigoIngreso . $ceros .",". $hora .",". $min .",". $mes .",". $dia .",". $anio . $cerosSeparados . $ceros . $ceros . ", 0.00, 0.00";
    array_push($datosArray, $allInOne);
}

//generacion txt como se tiene que subir a sistema
$date = getdate();
$dataCode = $date['mday'].$date['mon'].$date['year'];
$rutaTxt = "src/subirSirh/punch".$dataCode.".log";
validarExistencia($rutaTxt);

$createTxt = fopen($rutaTxt, 'w');

foreach($datosArray as $marcas){
    fwrite($createTxt, $marcas .PHP_EOL);
}
fclose($createTxt);


//generacion excel para visualizacion
$rutaExcel = "src/respaldoExcel/Marcas_".$dataCode.".xlsx";
$excelArray = array();
for($i = 0; $i < count($datosArray); $i ++){
    array_push($excelArray, explode(",", $datosArray[$i]));
}

for($i = 0; $i < count($excelArray); $i++){
    $sector = $excelArray[$i][0];
    $bio = $excelArray[$i][1];
    $entradaSalida = $excelArray[$i][2];
    $claveMarca = $excelArray[$i][3];
    $hrs = $excelArray[$i][5];
    $minu = $excelArray[$i][6];
    $month = $excelArray[$i][7];
    $day = $excelArray[$i][8];
    $year = $excelArray[$i][9];
    $completeDate = $day."/".$month."/20".$year;
    $completeHours = $hrs.":".$minu;

    array_push($arrayInsertExcel, [  intval($sector)
                                    , intval($bio)
                                    , intval($entradaSalida)
                                    , intval($claveMarca)
                                    , intval($hrs)
                                    , intval($minu)
                                    , intval($month)
                                    , intval($day)
                                    , intval($year)
                                    , $completeDate
                                    , $completeHours ]);

}

//lectura archivo excel
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivoExcel);


for($i = 0; $i < count($arrayInsertExcel); $i++){
    for($j = 0; $j < count($arrayInsertExcel[$i]); $j++){
        $fila = 1 + $j;
        $col = $columna + $i;
        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($fila, $col, $arrayInsertExcel[$i][$j]);
    }
}

validarExistencia($rutaExcel);

$writer = new Xlsx($spreadsheet);
$writer->save($rutaExcel);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversion Datos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/css/style.css">
</head>
<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                Conversion de Datos Horario 
            </a>
        </div>
    </nav>
    <div class="row justify-content-center">
        <div class="col-auto text-center">
           <div class="container mt-2">
                        
            <?php if(file_exists($rutaTxt)){ ?>
                <br />
                    <h4>Archivo Creado con Exito</h4>
                    <a class="btn btn-outline-success" href="index.php" role="button">Regresar al Menu Principal</a>
                <br />
            <?php }else{ ?>
                <br />
                    <h4>Error al Crear Archivo</h4>
                    <p>Recuerda que tiene que ser un archivo que termine con .dat si tiene otra terminacion no funcionara :D</p>
                    <a class="btn btn-outline-success" href="index.php" role="button">Volver a Cargar Archivo</a>                
                <br />
            <?php } ?>

           </div>
        </div>
    </div>

    <footer class="container-fluid">
        <nav class="navbar justify-content-center">
            <a class="navbar-brand ms-3" href="index.php">
               Convertidor Creado Con &#10084; Por Patricio Leon Ormazabal
            </a>
        </nav>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


