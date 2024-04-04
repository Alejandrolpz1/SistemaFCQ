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

// Verificar si se seleccionó un administrador para editar
if (isset($_GET['id'])) {
    $id_admin = $_GET['id'];

    // Obtener información del administrador seleccionado
    $admin = obtenerAdminPorId($id_admin);

    if (!$admin) {
        echo "Administrador no encontrado.";
        exit();
    }

    // Verificar si se envió el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $cargo = $_POST['cargo'];
        $usuario = $_POST['usuario'];
        $plaintext_password = $_POST['password']; // Contraseña en texto plano
        $password = password_hash($plaintext_password, PASSWORD_DEFAULT); // Hashear la contraseña

        // Llamar a la función para editar administrador
        $resultado = editarAdmin($id, $nombre, $apellido, $cargo, $usuario, $password);

        // Verificar el resultado
        if ($resultado) {
            $mensaje = "Administrador editado correctamente.";
            // Redirigir después de 2 segundos a la lista de administradores
            echo '<meta http-equiv="refresh" content="2;url=editaradmin.php">';
        } else {
            $mensaje = "Error al editar administrador.";
        }
    }
} else {
    // Obtener la lista de administradores si no se seleccionó uno para editar
    $admins = obtenerListaAdmins();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Administrador</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="IndexAdmin.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="IndexAdmin.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Editar Administrador</h1>
    </nav>
    
    <div class="contenedor2">
        <div class="parteu">
            <?php if (isset($admin)) : ?>
                <!-- Mostrar formulario de edición si se seleccionó un administrador -->
                <h2>Editar Administrador</h2>
                <?php if (isset($mensaje)) : ?>
                    <p><?php echo $mensaje; ?></p>
                <?php endif; ?>
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $admin['Id']; ?>" method="post">
                    <input type="hidden" name="id" value="<?php echo $admin['Id']; ?>">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="caja" name="nombre" value="<?php echo $admin['Nombre']; ?>" required><br>

                    <label for="apellido">Apellido:</label>
                    <input type="text" class="caja" name="apellido" value="<?php echo $admin['Apellido']; ?>" required><br>

                    <label for="cargo">Cargo:</label>
                    <input type="text" class="caja" name="cargo" value="<?php echo $admin['Cargo']; ?>" required><br>

                    <label for="usuario">Usuario:</label>
                    <input type="text" class="caja" name="usuario" value="<?php echo $admin['Usuario']; ?>" required><br>

                    <label for="password">Contraseña:</label>
                    <input type="password" class="caja" name="password" value="" required><br>

                    <input type="submit" class="Boton" value="Guardar Cambios">
                    <!-- Botón de cancelar para regresar a la lista de administradores -->
                    <a href="editaradmin.php" style="margin-left: 10px;"><button type="button" class="Boton2">Regresar</button></a>
                </form>
            <?php else : ?>
                <!-- Mostrar lista de administradores si no se ha seleccionado uno para editar -->
                <h2>Lista de Administradores</h2><br>
                <ul>
                    <?php foreach ($admins as $admin) : ?>
                        <li>
                            <?php echo $admin['Nombre'] . ' ' . $admin['Apellido']; ?>
                            <a href="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $admin['Id']; ?>"><button class="Boton">Editar</button></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <!-- Botón de cancelar para regresar al index -->
                <a href="indexadmin.php"><button class="Boton2">Cancelar</button></a>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
