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
include 'funciones.php';

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $numEmp = $_POST['numEmp'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $password = $_POST['password'];

    // Llamar a la función para agregar secretaria
    $resultado = agregarSecretaria($numEmp, $nombre, $apellido, $password);

    // Verificar el resultado
    if ($resultado) {
        $mensaje = "Secretaria agregada correctamente.";
        // Esperar 2 segundos antes de redirigir
        echo '<script>
                setTimeout(function() {
                    window.location.href = "indexAdmin.php";
                }, 2000);
              </script>';
    } else {
        $mensaje = "Error al agregar secretaria.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Secretaria</title>
</head>
<body>

    <h2>Agregar Secretaria</h2>

    <?php if (isset($mensaje)) : ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="numEmp">Número de Empleado:</label>
        <input type="text" name="numEmp" required><br>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br>

        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" required><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Agregar">
    </form>

</body>
</html>
