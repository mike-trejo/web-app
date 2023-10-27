<?php

function db_connection($servername, $username, $password, $db_name)
{
    try {
        $conn = new PDO("sqlsrv:server=$servername, 1433;database=$db_name; encrypt=0; TrustServerCertificate=1", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
      } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
      }
}

$name = $_POST['name'];
$lastname = $_POST['lastname'];

//Datos necesarios para la conexión a la DB
$server = "escuela.c4p95cxpg3rh.us-east-2.rds.amazonaws.com";
$user = "data_reader";
$pass = "Tuto&=l3Ruw?";
$db = "alwar";

$conn = db_connection($server, $user, $pass, $db);

spl_autoload_register(function ($TableRows) {
  include $TableRows . '.php';
});

echo"<!DOCTYPE html>";
echo"<html lang='en'>";
echo"<head>";
echo"<meta charset='UTF-80'>";
echo"<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo"<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC' crossorigin='anonymous'>";
echo"<link rel='stylesheet' type='text/css' href='./css/results.css'>";
echo"<title>Registro de accesos</title>";
echo"</head>";
echo"<body>";
echo"<div class='p-5 text-center bg-light'>
  <p class='display-1'>CETIS 29</p>
  <p class='h2'>Registro de accesos</p>
  </div>";

echo"<div class='container-fluid w-75'>";
echo"<br>";
echo "<table class='table table-striped table-hover'>";
echo "<tr><th>Nombre</th><th>Apellidos</th><th>Tipo</th><th>Fecha</th><th>Hora</th></tr>";


$stmt = $conn->prepare("SELECT UI.Name, UI.lastname, ACCM.event_point_name, FORMAT(ACCM.time, 'dd/MM/yyyy') AS Fecha, FORMAT(ACCM.time, 'HH:mm:ss') AS Hora
  FROM acc_monitor_log AS ACCM INNER JOIN USERINFO AS UI
  ON ACCM.card_no = UI.CardNo
  WHERE ACCM.card_no != 0 AND UI.Name = '{$name}' AND UI.lastname = '{$lastname}'
  ORDER BY ACCM.time DESC");
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }

$conn = null; //Cierra la conexión a la DB

echo "</table>";
echo"<br>";
echo"</div>";
echo"<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js' integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM' crossorigin='anonymous'></script>";
echo"<br>";
echo"<p><a href='./formulario.html'>Regresar</a></p>";
echo"</body>";
?>