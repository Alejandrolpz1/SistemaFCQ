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
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="IndexAdmin.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="IndexAdmin.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Agregar Profesor</h1>
    </nav>

    <div class="contenedor2">
        <h2>Agregar Profesor</h2><br>

        <?php if (isset($mensaje)) : ?>
            <p><?php echo $mensaje; ?></p>
        <?php endif; ?>
        
        <div class="parteu">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <center>
                    <label for="numEmp">Número de Empleado:</label>
                    <input type="number" class="caja" name="numEmp" required><br>

                    <label for="nombre">Nombre:</label><br>
                    <input type="text" class="caja" name="nombre" required><br>

                    <label for="apellidos">Apellidos:</label><br>
                    <input type="text" class="caja" name="apellidos" required><br>

                    <label for="correo">Correo:</label><br>
                    <input type="email" class="caja" name="correo" required><br>

                    <label for="telefono">Teléfono:</label><br>
                    <input type="tel" class="caja" name="telefono" required><br>

                    <label for="password">Contraseña:</label><br>
                    <input type="password" class="caja" name="password" required><br>

                    <input type="submit" class="Boton" value="Agregar">
                </center>
            </form>
        </div>
    </div>
</body>
</html>
