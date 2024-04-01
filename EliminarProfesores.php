<?php
include 'funciones.php';

session_start();


// Verificar la sesión del administrador
if (!isset($_SESSION['admin_usuario']) || empty($_SESSION['admin_usuario'])) {
    header("Location: LoginAdmin.php");
    exit();
}

// Verificar el tiempo de inactividad y cerrar sesión si es necesario
$inactivity_timeout = 1800; // 30 minutos (en segundos)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactivity_timeout)) {
    session_unset();
    session_destroy();
    header("Location: LoginAdmin.php");
    exit();
}

// Actualizar el tiempo de actividad
$_SESSION['last_activity'] = time();

// Verificar si se seleccionó un profesor para eliminar
if (isset($_GET['numEmp'])) {
    $numEmp = $_GET['numEmp'];

    // Llamar a la función para eliminar profesor
    $resultado = eliminarProfesor($numEmp);

    // Verificar el resultado
    if ($resultado) {
        $mensaje = "Profesor eliminado correctamente.";
        // Redirigir después de 2 segundos a la lista de profesores
        echo '<meta http-equiv="refresh" content="2;url=EliminarProfesores.php">';
    } else {
        $mensaje = "Error al eliminar profesor.";
    }
} else {
    // Obtener la lista de profesores si no se seleccionó uno para eliminar
    $profesores = obtenerListaProfesores();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Profesor</title>
</head>
<body>

    <?php if (isset($mensaje)) : ?>
        <h2><?php echo $mensaje; ?></h2>
    <?php else : ?>
        <h2>Lista de Profesores</h2>
        <ul>
            <?php foreach ($profesores as $profesor) : ?>
                <li>
                    <?php echo $profesor['Nombre'] . ' ' . $profesor['Apellidos']; ?>
                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?numEmp=' . $profesor['NumEmp']; ?>">Eliminar</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- Botón de cancelar para regresar al index -->
        <a href="indexAdmin.php">Cancelar </a>
    <?php endif; ?>

</body>
</html>
