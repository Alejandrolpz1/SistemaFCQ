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

// Verificar si se seleccionó un administrador para eliminar
if (isset($_GET['id'])) {
    $id_admin = $_GET['id'];

    // Obtener información del administrador seleccionado
    $admin = obtenerAdminPorId($id_admin);

    if (!$admin) {
        echo "Administrador no encontrado.";
        exit();
    }

    // Verificar si se confirmó la eliminación
    if (isset($_POST['confirmar'])) {
        // Llamar a la función para eliminar administrador
        $resultado = eliminarAdmin($id_admin);

        // Verificar el resultado
        if ($resultado) {
            $mensaje = "Administrador eliminado correctamente.";
            // Redirigir después de 2 segundos al index
            echo '<meta http-equiv="refresh" content="2;url=indexadmin.php">';
        } else {
            $mensaje = "Error al eliminar administrador.";
        }
    }
} else {
    // Obtener la lista de administradores si no se seleccionó uno para eliminar
    $admins = obtenerListaAdmins();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Administrador</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>
<body>
<nav>
        <ul>
            <li><a href="IndexAdmin.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="IndexAdmin.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Eliminar Administrador</h1>
    </nav>

    <?php if (isset($admin)) : ?>
        <!-- Mostrar confirmación de eliminación si se seleccionó un administrador -->
        <h2>Eliminar Administrador</h2>
        <?php if (isset($mensaje)) : ?>
            <p><?php echo $mensaje; ?></p>
        <?php else : ?>
            <div class = "contenedor2">
            <div class = "parteu"><p>¿Estás seguro de que deseas eliminar al administrador <?php echo $admin['Nombre'] . ' ' . $admin['Apellido']; ?>?</p>
            <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $admin['Id']; ?>" method="post">
                <input type="hidden" name="confirmar" value="1">
                <input type="submit" class = "Boton" value="Confirmar">
                <!-- Botón de cancelar para regresar a la lista de administradores -->
                <a href="indexadmin.php" style="margin-left: 10px;"><button type="button" class = "Boton2" >Cancelar</button></a>
            </form></div></div>
            
        <?php endif; ?>

        
        <?php else : ?>
            <div class = "contenedor2">
            <div class = "parteu"><!-- Mostrar lista de administradores si no se ha seleccionado uno para eliminar -->
        <h2>Lista de Administradores</h2><br>
        <ul>
            <?php foreach ($admins as $admin) : ?>
                <li>
                    <?php echo $admin['Nombre'] . ' ' . $admin['Apellido']; ?>
                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $admin['Id']; ?>"><button type="button" class = "Boton" >Eliminar</button></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- Botón de cancelar para regresar al index -->
        <a href="indexadmin.php"><button type="button" class = "Boton2" >Cancelar</button></a></div>

            
            
        </div>
        
    <?php endif; ?> 
    

</body>
</html>
