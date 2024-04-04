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
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="IndexAdmin.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="IndexAdmin.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Editar Profesor</h1>
    </nav>

    <?php if (isset($profesor)) : ?>
        <!-- Mostrar formulario de edición si se seleccionó un profesor -->
        <div class="contenedor2">
            <div class="parteu">
                <h2>Editar Profesor</h2>
                <?php if (isset($mensaje)) : ?>
                    <p><?php echo $mensaje; ?></p>
                <?php endif; ?>
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?numEmp=' . urlencode($profesor['NumEmp']); ?>" method="post">
                    <input type="hidden" name="numEmp" value="<?php echo $profesor['NumEmp']; ?>">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" class="caja" value="<?php echo $profesor['Nombre']; ?>" required><br>

                    <label for="apellidos">Apellidos:</label>
                    <input type="text" name="apellidos" class="caja" value="<?php echo $profesor['Apellidos']; ?>" required><br>

                    <label for="correo">Correo:</label>
                    <input type="email" name="correo" class="caja" value="<?php echo $profesor['Correo']; ?>" required><br>

                    <label for="telefono">Teléfono:</label>
                    <input type="tel" name="telefono" class="caja" value="<?php echo $profesor['Telefono']; ?>" required><br>

                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" class="caja" required><br> <!-- No se muestra la contraseña almacenada en la base de datos -->

                    <input type="submit" class="Boton" value="Guardar Cambios">
                    <!-- Botón de cancelar para regresar a la lista de profesores -->
                    <a href="indexAdmin.php" style="margin-left: 10px;"><button type="button" class="Boton2">Cancelar</button></a>
                </form>
            </div>
        </div>
    <?php else : ?>
        <div class="contenedor2">
            <div class="parteu">
                <!-- Mostrar lista de profesores si no se ha seleccionado uno para editar -->
                <center>
                    <h2>Lista de Profesores</h2><br>
                    <ul>
                        <?php foreach ($profesores as $profesor) : ?>
                            <li>
                                <?php echo $profesor['Nombre'] . ' ' . $profesor['Apellidos']; ?>
                                <a href="<?php echo $_SERVER['PHP_SELF'] . '?numEmp=' . urlencode($profesor['NumEmp']); ?>">Editar</a><br><br>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- Botón de cancelar para regresar al index -->
                    <a href="indexAdmin.php"><button type="button" class="Boton2">Cancelar</button></a>
                </center>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
