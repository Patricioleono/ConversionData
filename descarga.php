<?php
$date = getdate();
$dataCode = $date['mday'].$date['mon'].$date['year'];
$rutaTxt = "src/reloj_control_".$dataCode.".txt";


header("Content-disposition: attachment; filename=reloj_control_".$dataCode.".txt");
header("Content-type: application/txt");
readfile($rutaTxt);
?>