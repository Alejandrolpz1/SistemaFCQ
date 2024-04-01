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
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $password = $_POST['password'];

    // Hash de la contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Llamar a la función para agregar profesor
    $resultado = agregarProfesor($numEmp, $nombre, $apellidos, $correo, $telefono, $passwordHash);

    // Verificar el resultado
    if ($resultado) {
        $mensaje = "Profesor agregado correctamente.";
        // Redirigir después de 2 segundos a la lista de profesores
        echo '<meta http-equiv="refresh" content="2;url=indexAdmin.php">';
    } else {
        $mensaje = "Error al agregar profesor.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Profesor</title>
</head>
<body>

    <h2>Agregar Profesor</h2>

    <?php if (isset($mensaje)) : ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="numEmp">Número de Empleado:</label>
        <input type="number" name="numEmp" required><br>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br>

        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" required><br>

        <label for="correo">Correo:</label>
        <input type="email" name="correo" required><br>

        <label for="telefono">Teléfono:</label>
        <input type="tel" name="telefono" required><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Agregar">
    </form>

</body>
</html>
