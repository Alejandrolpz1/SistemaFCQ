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

// Verificar si se seleccionó un profesor para editar
if (isset($_GET['numEmp'])) {
    $numEmp = $_GET['numEmp'];

    // Obtener información del profesor seleccionado
    $profesor = obtenerProfesorPorNumEmp($numEmp);

    if (!$profesor) {
        echo "Profesor no encontrado.";
        exit();
    }

    // Verificar si se envió el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $numEmp = $_POST['numEmp'];
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $password = $_POST['password'];

        // Hash de la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Llamar a la función para editar profesor
        $resultado = editarProfesor($numEmp, $nombre, $apellidos, $correo, $telefono, $passwordHash);

        // Verificar el resultado
        if ($resultado) {
            $mensaje = "Profesor editado correctamente.";
            // Redirigir después de 2 segundos a la lista de profesores
            echo '<meta http-equiv="refresh" content="2;url=indexAdmin.php">';
        } else {
            $mensaje = "Error al editar profesor.";
        }
    }
} else {
    // Obtener la lista de profesores si no se seleccionó uno para editar
    $profesores = obtenerListaProfesores();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Profesor</title>
</head>
<body>

    <?php if (isset($profesor)) : ?>
        <!-- Mostrar formulario de edición si se seleccionó un profesor -->
        <h2>Editar Profesor</h2>
        <?php if (isset($mensaje)) : ?>
            <p><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?numEmp=' . urlencode($profesor['NumEmp']); ?>" method="post">
            <input type="hidden" name="numEmp" value="<?php echo $profesor['NumEmp']; ?>">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $profesor['Nombre']; ?>" required><br>

            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" value="<?php echo $profesor['Apellidos']; ?>" required><br>

            <label for="correo">Correo:</label>
            <input type="email" name="correo" value="<?php echo $profesor['Correo']; ?>" required><br>

            <label for="telefono">Teléfono:</label>
            <input type="tel" name="telefono" value="<?php echo $profesor['Telefono']; ?>" required><br>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" required><br> <!-- No se muestra la contraseña almacenada en la base de datos -->

            <input type="submit" value="Guardar Cambios">
            <!-- Botón de cancelar para regresar a la lista de profesores -->
            <a href="indexAdmin.php" style="margin-left: 10px;">Cancelar</a>
        </form>
    <?php else : ?>
        <!-- Mostrar lista de profesores si no se ha seleccionado uno para editar -->
        <h2>Lista de Profesores</h2>
        <ul>
            <?php foreach ($profesores as $profesor) : ?>
                <li>
                    <?php echo $profesor['Nombre'] . ' ' . $profesor['Apellidos']; ?>
                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?numEmp=' . urlencode($profesor['NumEmp']); ?>">Editar</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- Botón de cancelar para regresar al index -->
        <a href="indexAdmin.php">Cancelar</a>
    <?php endif; ?>

</body>
</html>
