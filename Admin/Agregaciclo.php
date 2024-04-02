<?php
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

include('../funciones.php');

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $ciclo_escolar = $_POST['ciclo_escolar'];

    // Llamar a la función para agregar ciclo escolar
    $resultado = agregarCicloEscolar($ciclo_escolar);

    // Verificar el resultado
    if ($resultado) {
        $mensaje = "Ciclo escolar agregado correctamente.";
        // Redirigir después de 2 segundos a la lista de ciclos escolares
        echo '<meta http-equiv="refresh" content="2;url=indexAdmin.php">';
    } else {
        $mensaje = "Error al agregar ciclo escolar.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Ciclo Escolar</title>
</head>
<body>

    <h2>Agregar Ciclo Escolar</h2>

    <?php if (isset($mensaje)) : ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="ciclo_escolar">Ciclo Escolar:</label>
        <input type="text" name="ciclo_escolar" required><br>

        <input type="submit" value="Agregar">

        <a href="IndexAdmin.php">Cancelar</a>
    </form>

</body>
</html>