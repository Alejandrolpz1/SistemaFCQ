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

// Verificar si se ha seleccionado un administrador para ver su contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario'])) {
    $usuario = $_POST['usuario'];
    $contraseña = obtenerContraseñaAdmin($usuario);
    if (!$contraseña) {
        $mensaje_error = "Error: No se pudo obtener la contraseña del administrador.";
    }
}

// Obtener la lista de administradores disponibles
$administradores = obtenerListaAdmins();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contraseñas Administradores</title>
</head>
<body>
    <h2>Contraseñas Administradores</h2>
    
    <?php if (isset($mensaje_error)) : ?>
        <p><?php echo $mensaje_error; ?></p>
    <?php endif; ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="usuario">Seleccionar Administrador:</label>
        <select name="usuario" required>
            <?php foreach ($administradores as $admin) : ?>
                <option value="<?php echo $admin['Usuario']; ?>"><?php echo $admin['Nombre'] . ' ' . $admin['Apellido']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Ver Contraseña">
    </form>

    <?php if (isset($contraseña)) : ?>
        <h3>Contraseña de <?php echo $usuario; ?>:</h3>
        <p><?php echo $contraseña; ?></p>
    <?php endif; ?>

    <a href="IndexAdmin.php">Cancelar</a>
</body>
</html>
