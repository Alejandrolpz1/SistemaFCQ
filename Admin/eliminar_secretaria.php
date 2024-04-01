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

// Verificar si se seleccionó una secretaria para eliminar
if (isset($_GET['numEmp'])) {
    $numEmp = $_GET['numEmp'];

    // Obtener información de la secretaria seleccionada
    $secretaria = obtenerSecretariaPorNumEmp($numEmp);

    if (!$secretaria) {
        echo "Secretaria no encontrada.";
        exit();
    }

    // Verificar si se envió el formulario de eliminación
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Llamar a la función para eliminar secretaria
        $resultado = eliminarSecretaria($numEmp);

        // Verificar el resultado
        if ($resultado) {
            $mensaje = "Secretaria eliminada correctamente.";
            // Redirigir después de 2 segundos a la lista de secretarias
            echo '<meta http-equiv="refresh" content="2;url=eliminar_secretaria.php">';
        } else {
            $mensaje = "Error al eliminar secretaria.";
        }
    }
} else {
    // Obtener la lista de secretarias si no se seleccionó una para eliminar
    $secretarias = obtenerListaSecretarias();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Secretaria</title>
</head>
<body>

    <?php if (isset($secretaria)) : ?>
        <!-- Mostrar formulario de eliminación si se seleccionó una secretaria -->
        <h2>Eliminar Secretaria</h2>
        <?php if (isset($mensaje)) : ?>
            <p><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?numEmp=' . $secretaria['NumEmp']; ?>" method="post">
            <input type="hidden" name="numEmp" value="<?php echo $secretaria['NumEmp']; ?>">
            <p>¿Estás seguro de que quieres eliminar a <?php echo $secretaria['Nombre'] . ' ' . $secretaria['Apellido']; ?>?</p>
            <input type="submit" value="Eliminar">
            <!-- Botón de cancelar para regresar a la lista de secretarias -->
            <a href="eliminar_secretaria.php" style="margin-left: 10px;">Cancelar</a>
        </form>
    <?php else : ?>
        <!-- Mostrar lista de secretarias si no se ha seleccionado una para eliminar -->
        <h2>Lista de Secretarias</h2>
        <ul>
            <?php foreach ($secretarias as $secretaria) : ?>
                <li>
                    <?php echo $secretaria['Nombre'] . ' ' . $secretaria['Apellido']; ?>
                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?numEmp=' . $secretaria['NumEmp']; ?>">Eliminar</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- Botón de cancelar para regresar al index -->
        <a href="indexadmin.php">Cancelar</a>
    <?php endif; ?>

</body>
</html>
