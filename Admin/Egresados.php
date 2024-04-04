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

// Mensajes
$mensaje_error = "";
$mensaje_exito = "";

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_egresados'])) {
    // Ejecutar la función para actualizar el estatus de los egresados
    $result = actualizarEstatusEgresados();

    if ($result) {
        $mensaje_exito = "Estatus de egresados actualizado correctamente.";
    } else {
        $mensaje_error = "Error al actualizar el estatus de los egresados.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Estatus de Egresados</title>
</head>
<body>

    <h2>Actualizar Estatus de Egresados</h2>

    <?php if (!empty($mensaje_exito)) : ?>
        <p style="color: green;"><?php echo $mensaje_exito; ?></p>
    <?php endif; ?>

    <?php if (!empty($mensaje_error)) : ?>
        <p style="color: red;"><?php echo $mensaje_error; ?></p>
    <?php endif; ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="submit" name="actualizar_egresados" value="Actualizar Egresados">
    </form>

    <a href="IndexAdmin.php">Cancelar</a>

</body>
</html>

