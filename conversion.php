<?php
function sanitizeTabReturn( $str )
{   $strLimpio = trim($str);
    $fCleaner = preg_replace( '/[\n\r\t]+/', '-', $strLimpio);
    return preg_replace( '/[\n\r\t]+/', '-', $fCleaner);
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

$date = getdate();
$dataCode = $date['mday'].$date['mon'].$date['year'];
$rutaTxt = "src/reloj_control_".$dataCode.".txt";
$createTxt = fopen($rutaTxt, 'w');

foreach($datosArray as $marcas){
    fwrite($createTxt, $marcas .PHP_EOL);
}
fclose($createTxt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversion Datos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
            Conversion de Datos Horario Creado Con 	&#10084; Por Patricio Leon Ormazabal
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
                    <a class="btn btn-outline-success" href="index.php" role="button">Regresar al Menu Principal</a>                <br />
            <?php } ?>

           </div>
        </div>
    </div>
    <script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


