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
    <title>Eliminar Alumno</title>
    <link rel="stylesheet" href="../css/csssecretaria.css"> 
</head>

<body>
    <nav>
        <ul>
            <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Eliminando Estudiantes...</h1>
    </nav>
    </div>
            <div class="loader">
            <div class="justify-content-center jimu-primary-loading"></div>
        </div>
    <div class="contenedor2">
        <div class="parte3">
            <?php 
            include('../funciones.php');
            $carrera = $_POST["carrera"];
            $estatus = $_POST["estatus"];
            $registros = eliminarGruposAlumnos($carrera, $estatus);   
                     
            ?>
            
        </div>
    </div>
</body>
</html>