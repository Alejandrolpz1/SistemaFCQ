<?php
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: LoginProfesores.php");
    exit();
}

$inactive_time = 1800;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive_time)) {
    // Si ha pasado demasiado tiempo, cerrar sesión
    session_unset();
    session_destroy();
    header("Location: LoginProfesores.php");
    exit();
}

// Actualizar la marca de tiempo de la última actividad
$_SESSION['last_activity'] = time();

include('../funciones.php');

// Obtener el número de empleado del profesor que ha iniciado sesión
$numEmpProfesor = $_SESSION['user_id'];

// Obtener el nombre del profesor
$_SESSION['nombre'] = obtenerNombreProfesor($numEmpProfesor);

// Obtener las materias asociadas al profesor
$materiasAsociadas = obtenerMateriasAsociadas2($numEmpProfesor); // Cambio aquí
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index de Profesores</title>
</head>
<body>
    <h2>Bienvenido, Profesor <?php echo $_SESSION['nombre']; ?>!</h2>

    <?php 
    if ($materiasAsociadas) {
        echo "<p>Tus materias asociadas son:</p>";
        echo "<ul>";
        foreach ($materiasAsociadas as $materia) {
            // Agrega un formulario con método POST para enviar los datos a detallesmaterias.php
            echo "<li><form action='detallesmaterias.php' method='post'>";
            echo "<input type='hidden' name='numEmpProfesor' value='$numEmpProfesor'>";
            echo "<input type='hidden' name='nombreMateria' value='{$materia['Materia']}'>";
            echo "<input type='hidden' name='claveMateria' value='{$materia['ClaveMateria']}'>";
            echo "<button type='submit'>{$materia['Materia']}</button>";
            echo "</form></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No estás asociado a ninguna materia actualmente.</p>";
    }
    ?>

    <a href="LogoutProfesores.php">Cerrar sesión</a>
</body>
</html>
