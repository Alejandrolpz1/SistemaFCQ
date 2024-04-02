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

// Definir una variable para el mensaje de éxito
$mensaje_exito = "";

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ciclo escolar seleccionado
    $ciclo_escolar = $_POST['ciclo_escolar'];

    // Obtener la información del ciclo seleccionado
    $ciclo = obtenerCicloEscolarPorId($ciclo_escolar);

    if (!$ciclo) {
        $mensaje = "Error: Ciclo escolar no encontrado.";
    } else {
        // Llamar a la función para agregar ciclo escolar activo
        $resultado = activarCicloEscolar($ciclo['id'], $ciclo['ciclo']);

        // Verificar el resultado
        if ($resultado) {
            // Establecer el mensaje de éxito
            $mensaje_exito = "Ciclo escolar actualizado correctamente.";
            // Redirigir después de 3 segundos a la página principal
            echo '<meta http-equiv="refresh" content="3;url=indexAdmin.php">';
        } else {
            $mensaje = "Error al agregar ciclo escolar activo.";
        }

        // Llamar a la función para aumentar el semestre de los alumnos
        aumentarSemestreAlumnos();
    }
}

// Obtener la lista de ciclos escolares disponibles
$ciclos_escolares = obtenerCiclosEscolares1();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Ciclo Escolar Activo</title>
</head>
<body>

    <h2>Seleccionar Ciclo Escolar Activo</h2>

    <?php if (!empty($mensaje_exito)) : ?>
        <script>
            // Mostrar mensaje emergente
            alert("<?php echo $mensaje_exito; ?>");
        </script>
    <?php endif; ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="ciclo_escolar">Seleccionar Ciclo Escolar:</label>
        <select name="ciclo_escolar" required>
            <?php foreach ($ciclos_escolares as $ciclo) : ?>
                <option value="<?php echo $ciclo['id']; ?>"><?php echo $ciclo['ciclo']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <input type="submit" value="Seleccionar">

        <a href="IndexAdmin.php">Cancelar</a>
    </form>

</body>
</html>
