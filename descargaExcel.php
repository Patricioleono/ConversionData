<?php
$date = getdate();
$dataCode = $date['mday'].$date['mon'].$date['year'];
$rutaExcel = "src/respaldoExcel/Marcas_".$dataCode.".xlsx";

header("Content-disposition: attachment; filename=Marcas_".$dataCode.".xlsx");
header("Content-type: application/xlsx");
readfile($rutaExcel);
?>