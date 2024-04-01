<?php
session_start();
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: LoginSecretarias.php");
    exit();
}
$inactive_time = 1800;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive_time)) {
    // Si ha pasado demasiado tiempo, cerrar sesión
    session_unset();
    session_destroy();
    header("Location: LoginSecretarias.php");
    exit();
}
// Actualizar la marca de tiempo de la última actividad
$_SESSION['last_activity'] = time();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>confirmacion Baja</title> 
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>
<body>
<nav>
    <ul>
        <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
    </ul>
    <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Confirmación de Alta</h1>
</nav>
<?php
$matriculalumno = isset($_GET['Matricula_Alumno']) ? filter_var($_GET['Matricula_Alumno'], FILTER_SANITIZE_STRING) : '';
$carrera = isset($_GET['formacion']) ? filter_var($_GET['formacion'], FILTER_SANITIZE_STRING) : '';
include('../funciones.php');
$registros = consultarAlumnosWhereMatricula($matriculalumno);
?>
<div class="contenedor2">
    <h4>¿Estás seguro de dar de alta al alumno?</h4>
    <form method="POST" action="ListaAlumnosBaja.php" name="">
        <input class="controls" type="hidden" id="carrera" name="carrera" value="<?php echo $carrera ?> ">
        <input class="Boton" type="submit" value="Regresar">
    </form>
    <form method="post" action="Alta.php">
        <input type="hidden" name="matricula" value="<?php echo $registros['Matricula'];?>">
        <input class="Boton" type="submit" value="Si">
    </form>
</div>
</body>
</html>