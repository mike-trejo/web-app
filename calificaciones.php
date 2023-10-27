<?php

function db_connection($servername, $username, $password, $db_name)
{
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$db_name", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
      } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
      }
}

$conn = db_connection("localhost", "data_reader", "Tuto&=l3Ruw?", "db_calificaciones");
$curp = $_POST['curp'];

spl_autoload_register(function ($TableRows) {
  include $TableRows . '.php';
});

$stmt_1 = $conn->prepare("SELECT alumno.NOMBRE, alumno.PATERNO, alumno.MATERNO, alumno.CARRERA, alumno.GRUPO, alumno.GENERACION, alumno.TURNO, calificacion.PERIODO FROM alumno
JOIN calificacion_alumno ON alumno.NO_CONTROL = calificacion_alumno.NC_ALUMNO
JOIN calificacion ON calificacion_alumno.ID_CAL = calificacion.ID_CAL
JOIN asignatura ON calificacion.ID_ASIGNATURA = asignatura.ID_ASIGNATURA
WHERE alumno.CURP = '{$curp}'");

$stmt_2 = $conn->prepare("SELECT asignatura.NOMBRE, calificacion.PARCIAL_1, calificacion.PARCIAL_2, calificacion.PARCIAL_3, calificacion.FINAL
FROM alumno
JOIN calificacion_alumno ON alumno.NO_CONTROL = calificacion_alumno.NC_ALUMNO
JOIN calificacion ON calificacion_alumno.ID_CAL = calificacion.ID_CAL
JOIN asignatura ON calificacion.ID_ASIGNATURA = asignatura.ID_ASIGNATURA
WHERE alumno.CURP = '{$curp}'");

//Execute the query
$stmt_1->execute();
$stmt_2->execute();

// set the resulting to associative array
$stmt_1->setFetchMode(PDO::FETCH_ASSOC);
$stmt_2->setFetchMode(PDO::FETCH_ASSOC);

$alumno = $stmt_1->fetch(PDO::FETCH_ASSOC);
$calificacion = $stmt_2->fetchAll();

echo '
<!doctype html>
<html lang="en">
  <head>
  <style>
    .div{
      float: left;
      padding: 15px; 
    }
  </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consulta de calificaciones.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
    <body>
      <div class="p-5 text-center bg-light">
        <p class="display-1">CETIS 29</p>
        <p class="h2">Calificaciones</p>
      </div>
      <div class="div container-fluid w-50">
        <table class="table table-hover table-responsive">
          <tr>
            <th><h3>Datos del alumno</h3></th>
            <th></th>
          </tr>
          <tr>
            <td>Nombre</td>
            <td>'.$alumno['NOMBRE'].' '.$alumno['PATERNO'].' '.$alumno['MATERNO'].'</td>
          </tr>
          <tr>
            <td>Carrera</td>
            <td>'.$alumno['CARRERA'].'</td>
          </tr>
          <tr>
            <td>Grupo</td>
            <td>'.$alumno['GRUPO'].'</td>
          </tr>
          <tr>
            <td>Turno</td>
            <td>'.mb_strtoupper($alumno['TURNO']).'</td>
          </tr>
          <tr>
            <td>Periodo</td>
            <td>'.$alumno['PERIODO'].'</td>
          </tr>
        </table>
      </div>';
      echo 
      '<div class="container-fluid">
        <br>
        <br>
        <table class="table table-striped table-hover">
        <tr><th>Asignatura</th><th>Parcial 1</th><th>Parcial 2</th><th>Parcial 3</th><th>Final</th></tr>';
        foreach(new TableRows(new RecursiveArrayIterator($calificacion)) as $k=>$v) {
          echo $v;
        }
      echo'
        </table>
      </div>
      <br>
      <p><ul class="nav justify-content-center">
      <li class="nav-item">
        <a class="btn btn-outline-dark" href="./calificaciones.html">Regresar</a>
      </li>
    </ul></p>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>';

$conn = null;

?>