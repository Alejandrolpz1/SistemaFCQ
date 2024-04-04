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
    <title>confirmacion eliminar estudiante</title> 
    <link rel="stylesheet" href="../css/csssecretaria.css"> 
</head>
<body>
    <nav>
        <ul>
            <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Eliminar Alumnos Egresados</h1>
    </nav>
    <?php
    $carrera = isset($_POST['carrera']) ? filter_var($_POST['carrera'], FILTER_SANITIZE_STRING) : '';
    ?>
    <div class="contenedor2">
        <div class="parte3">
            <h4>¿Está seguro de eliminar permanentemente a todos los estudiantes egresados?</h4>
            <p style="color: red; font-size: 16px;">Esta acción es irreversible y resultará en la eliminación de todos los registros relacionados con los estudiantes en este estado, incluyendo su información personal y calificaciones.</p>
            <form method="get" action="ListaAlumnosEgresados.php" name="">
                <input class="controls" type="hidden" id="carrera" name="carrera" value="<?php echo $carrera ?> ">
                <input class="Boton" type="submit" value="Regresar">
            </form>
            <form method="post" action="EliminarGruposAlumnos.php">
                <input class="controls" type="hidden" id="estatus" name="estatus" value="Egresado">
                <input class="controls" type="hidden" id="carrera" name="carrera" value="<?php echo $carrera ?> ">
                <input class="Boton" type="submit" value="Eliminar">
            </form>
        </div>
    </div>
</body>
</html>

