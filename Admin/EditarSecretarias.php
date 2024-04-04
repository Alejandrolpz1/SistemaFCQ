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
        $plaintext_password = $_POST['password'];
        $password = password_hash($plaintext_password, PASSWORD_DEFAULT); // Hashear la contraseña

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
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>

<body>
    <nav>
        <ul>
            <li><a href="IndexAdmin.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="IndexAdmin.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Editar Secretarias</h1>
    </nav>

    <?php if (isset($secretaria)) : ?>
    <div class="contenedor2">
        <h2>Editar Secretaria</h2><br>
        <?php if (isset($mensaje)) : ?>
            <p><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <div class="parteu">
            <form action="<?php echo $_SERVER['PHP_SELF'] . '?numEmp=' . $secretaria['NumEmp']; ?>" method="post">
                <input type="hidden" name="numEmp" value="<?php echo $secretaria['NumEmp']; ?>">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" class="caja" value="<?php echo $secretaria['Nombre']; ?>" required><br>

                <label for="apellido">Apellido:</label>
                <input type="text" name="apellido" class="caja" value="<?php echo $secretaria['Apellido']; ?>" required><br>

                <label for="password">Contraseña:</label>
                <input type="password" name="password" class="caja" value="" required><br>

                <input type="submit" class="Boton" value="Guardar Cambios">
                <!-- Botón de cancelar para regresar a la lista de secretarias -->
                <a href="IndexAdmin.php" style="margin-left: 10px;"><button type="button" class="Boton2">Cancelar</button></a>
            </form>
        </div>
    </div>
    <!-- Mostrar formulario de edición si se seleccionó una secretaria -->
    <?php else : ?>
    <div class="contenedor2">
        <div class="parteu">
            <!-- Mostrar lista de secretarias si no se ha seleccionado una para editar -->
            <h2>Lista de Secretarias</h2><br>
            <ul>
                <?php foreach ($secretarias as $secretaria) : ?>
                <li>
                    <?php echo $secretaria['Nombre'] . ' ' . $secretaria['Apellido']; ?>
                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?numEmp=' . $secretaria['NumEmp']; ?>">Editar</a><br><br>
                </li>
                <?php endforeach; ?>
            </ul>
            <!-- Botón de cancelar para regresar al index -->
            <a href="IndexAdmin.php"><button type="button" class="Boton2">Cancelar</button></a>
        </div>
    </div>
    <?php endif; ?>

</body>

</html>
