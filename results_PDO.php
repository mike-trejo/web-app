<?php
$name = $_POST['name'];
$lastname = $_POST['lastname'];

//Datos necesarios para la conexión a la DB
$servername = "escuela.c4p95cxpg3rh.us-east-2.rds.amazonaws.com";
$username = "data_reader";
$password = "Tuto&=l3Ruw?";
$dbname = "alwar";

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
echo "<tr><th>Nombre</th><th>Apellidos</th><th>Fecha</th><th>Hora</th></tr>";


//Genera la tabla
class TableRows extends RecursiveIteratorIterator {
  function __construct($it) {
    parent::__construct($it, self::LEAVES_ONLY);
  }

  function current() {
    return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
  }

  function beginChildren() {
    echo "<tr>";
  }

  function endChildren() {
    echo "</tr>" . "\n";
  }
}

//Realiza la conexxipon a la DB mediante PDO
try {
  $conn = new PDO("sqlsrv:server=$servername;database=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  $stmt = $conn->prepare("SELECT USERINFO.Name, USERINFO.lastname, FORMAT(CHECKINOUT.CHECKTIME, 'dd/MM/yyyy') AS Date, FORMAT(CHECKINOUT.CHECKTIME, 'HH:mm:ss') AS Time 
  FROM USERINFO INNER JOIN CHECKINOUT ON USERINFO.USERID = CHECKINOUT.USERID 
  WHERE USERINFO.lastname = '{$lastname}' AND USERINFO.Name = '{$name}' ORDER BY CHECKINOUT.CHECKTIME DESC");
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }
  
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
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