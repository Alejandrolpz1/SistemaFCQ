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
            <li><a href="SeleccionarCarreraXSemestre.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Procediendo Baja</h1>
    </nav>
<?php
$matriculalumno = isset($_GET['Matricula_Alumno']) ? filter_var($_GET['Matricula_Alumno'], FILTER_SANITIZE_STRING) : '';
$carrera = isset($_GET['Clave']) ? filter_var($_GET['Clave'], FILTER_SANITIZE_STRING) : '';
$semestre = isset($_GET['Semestre']) ? filter_var($_GET['Semestre'], FILTER_SANITIZE_STRING) : '';
$grupo = isset($_GET['Grupo']) ? filter_var($_GET['Grupo'], FILTER_SANITIZE_STRING) : '';


include('../funciones.php');
$registros = consultarAlumnosWhereMatricula($matriculalumno);
?>
<div class="contenedor2">
    <div class="parte3">
    <h4>¿Estás seguro de dar de baja al alumno?</h4>
    <form method="POST" action="ListaAlumnos.php" name="">
        <input class="controls" type="hidden" id="carrera" name="carrera" value="<?php echo $carrera ?> ">
        <input class="controls" type="hidden" id="semestre" name="semestre" value="<?php echo $semestre ?> ">
        <input class="controls" type="hidden" id="grupo" name="grupo" value="<?php echo $grupo ?> ">
        <input class="Boton" type="submit" value="No">
    </form>
    <form method="post" action="Baja.php">
        <input type="hidden" name="matriculadefinitiva" value="<?php echo $registros['Matricula'];?>">
        <input class="Boton" type="submit" value="Baja Definitiva">

    </form>
    <form method="post" action="Baja.php">
        <input type="hidden" name="matriculatemporal" value="<?php echo $registros['Matricula'];?>">
        <input class="Boton" type="submit" value="Baja Temporal">
        
    </form>
    </div>
</div>
</body>
</html>
