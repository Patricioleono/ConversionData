<?php
$date = getdate();
$dataCode = $date['mday'].$date['mon'].$date['year'];
$rutaTxt = "src/subirSirh/punch".$dataCode.".log";

header("Content-disposition: attachment; filename=punch".$dataCode.".log");
header("Content-type: application/txt");
readfile($rutaTxt);
?>