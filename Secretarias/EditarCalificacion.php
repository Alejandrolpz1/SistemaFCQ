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
    <title>Editar Calificaciones</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>

<body>
    <nav>
        <ul>
            <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Editar Calificaciones</h1>
    </nav>

    <?php 
        $matriculalumno = isset($_GET['Matricula_Alumno']) ? filter_var($_GET['Matricula_Alumno'], FILTER_SANITIZE_STRING) : '';
        $claveMateria = isset($_GET['Clave_Materia']) ? filter_var($_GET['Clave_Materia'], FILTER_SANITIZE_STRING) : '';
        $calificacion = isset($_GET['Calificacion']) ? filter_var($_GET['Calificacion'], FILTER_SANITIZE_STRING) : '';
        $nombrealumno = isset($_GET['Nombre']) ? filter_var($_GET['Nombre'], FILTER_SANITIZE_STRING) : '';
        $apellidoAlunmo = isset($_GET['Apellido']) ? filter_var($_GET['Apellido'], FILTER_SANITIZE_STRING) : '';
        $nombremateria = isset($_GET['Materia']) ? filter_var($_GET['Materia'], FILTER_SANITIZE_STRING) : '';
        $ciclo = isset($_GET['Ciclo']) ? filter_var($_GET['Ciclo'], FILTER_SANITIZE_STRING) : '';
        $carrera = isset($_GET['Carrera']) ? filter_var($_GET['Carrera'], FILTER_SANITIZE_STRING) : '';

        include('../funciones.php');
        $registros = AlumnosyCalificaciones2($matriculalumno);
    ?>

    <div class="contenedor2">
        <h3>Corrección calificación del alumno <?php echo htmlspecialchars($nombrealumno); ?> <?php echo htmlspecialchars($apellidoAlunmo); ?> en la materia <?php echo htmlspecialchars($nombremateria); ?> en el ciclo <?php echo htmlspecialchars($ciclo); ?></h3>
        <br>
        <p>Nota: Utiliza esta herramienta solo en caso de ERRORES en las calificaciones.</p>
        <div class="parte2">
            <form method="POST" action="ActualizarCalificaciones.php">
                <input type="hidden" id="matricula" name="matricula" value="<?php echo $registros[0]['Matricula_Alumno'];?>" required><br>
                <input class="controls" type="hidden" id="clave" name="clave" value="<?php echo $registros[0]['Clave_Materia'];?>" required>
                <label for="calificacion">Calificación:</label><br>
                <input type="number" class="caja" id="calificacion" name="calificacion" placeholder="Calificación" min="0" max="10" step="0.1" required><br>
                <input type="submit" class="Boton2" value="Editar">
            </form>
            <form method="get" action="ListaCalificacionesEditar.php">
                <input type="hidden" name="Clave_Materia" value="<?php echo $claveMateria;?>">
                <input type="hidden" name="Materia" value="<?php echo $nombremateria;?>">
                <input type="hidden" name="Ciclo_Escolar" value="<?php echo $ciclo;?>">
                <input type="hidden" name="Carrera" value="<?php echo $carrera;?>">
                <input type="hidden" name="Semestre" value="<?php echo $registros[0]['Semestre'];?>">
                <input type="hidden" name="Grupo" value="<?php echo $registros[0]['Grupo'];?>">
                <input class="Boton2" type="submit" value="Regresar">
            </form>
        </div>
    </div>
</body>

</html>