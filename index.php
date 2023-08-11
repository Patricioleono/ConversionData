
<?php 
$date = getdate();
$dataCode = $date['mday'].$date['mon'].$date['year'];
$rutaTxt = "src/subirSirh/punch".$dataCode.".log";
$rutaExcel = "src/respaldoExcel/Marcas_".$dataCode.".xlsx";

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
                <br />
                    <h4>Ingrese Archivo a Convertir</h4>
                <br />
                <form method="POST" action="conversion.php" enctype="multipart/form-data">
                    <div class="row">
                        <div class="input-group">
                            <input type="file" name="data" class="form-control" id="fileToUpload" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                            <button class="btn btn-outline-secondary" type="submit" id="inputGroupFileAddon04">Convertir</button>
                        </div>
                    </div>
                </form>
                <div class="container mt-2">

                    <?php if(file_exists($rutaTxt)){ ?>
                        <br />
                            <a class="btn btn-outline-primary" href="descarga.php" role="button">Descargar Archivo Log</a>
                        <br />
                    <?php }else{ ?>
                        <br />
                    <?php } ?>

                    
                    <?php if(file_exists($rutaExcel)){ ?>
                        <br />
                            <a class="btn btn-outline-primary" href="descargaExcel.php" role="button">Descargar Respaldo Excel</a>
                        <br />
                    <?php }else{ ?>
                        <br />
                    <?php } ?>
                   
                </div>
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

