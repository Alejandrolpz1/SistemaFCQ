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

// Verificar si se seleccionó una secretaria para editar
if (isset($_GET['numEmp'])) {
    $numEmp = $_GET['numEmp'];

    // Obtener información de la secretaria seleccionada
    $secretaria = obtenerSecretariaPorNumEmp($numEmp);

    if (!$secretaria) {
        echo "Secretaria no encontrada.";
        exit();
    }

    // Verificar si se envió el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $numEmp = $_POST['numEmp'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $password = $_POST['password'];

        // Llamar a la función para editar secretaria
        $resultado = editarSecretaria($numEmp, $nombre, $apellido, $password);

        // Verificar el resultado
        if ($resultado) {
            $mensaje = "Secretaria editada correctamente.";
            // Redirigir después de 2 segundos a la lista de secretarias
            echo '<meta http-equiv="refresh" content="2;url=IndexAdmin.php">';
        } else {
            $mensaje = "Error al editar secretaria.";
        }
    }
} else {
    // Obtener la lista de secretarias si no se seleccionó una para editar
    $secretarias = obtenerListaSecretarias();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Secretaria</title>
</head>
<body>

    <?php if (isset($secretaria)) : ?>
        <!-- Mostrar formulario de edición si se seleccionó una secretaria -->
        <h2>Editar Secretaria</h2>
        <?php if (isset($mensaje)) : ?>
            <p><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?numEmp=' . $secretaria['NumEmp']; ?>" method="post">
            <input type="hidden" name="numEmp" value="<?php echo $secretaria['NumEmp']; ?>">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $secretaria['Nombre']; ?>" required><br>

            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" value="<?php echo $secretaria['Apellido']; ?>" required><br>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" value="<?php echo $secretaria['Password']; ?>" required><br>

            <input type="submit" value="Guardar Cambios">
            <!-- Botón de cancelar para regresar a la lista de secretarias -->
            <a href="IndexAdmin.php" style="margin-left: 10px;">Cancelar?</a>
        </form>
    <?php else : ?>
        <!-- Mostrar lista de secretarias si no se ha seleccionado una para editar -->
        <h2>Lista de Secretarias</h2>
        <ul>
            <?php foreach ($secretarias as $secretaria) : ?>
                <li>
                    <?php echo $secretaria['Nombre'] . ' ' . $secretaria['Apellido']; ?>
                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?numEmp=' . $secretaria['NumEmp']; ?>">Editar</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- Botón de cancelar para regresar al index -->
        <a href="IndexAdmin.php">Cancelar</a>
    <?php endif; ?>

</body>
</html>
