<?php
$curp = $_GET['curp'];
$file_path = 'http://localhost:8080/escuela.com/Certificados/'.$curp.'.pdf';
$file_name = $curp.'.pdf';
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $file_name . '"');
readfile($file_path);
?>
