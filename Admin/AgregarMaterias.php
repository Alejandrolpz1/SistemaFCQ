<?php
session_start();

// Verificar la sesión del administrador
if (!isset($_SESSION['admin_usuario']) || empty($_SESSION['admin_usuario'])) {
    header("Location: LoginAdmin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Materias</title>
</head>
<body>
    <h2>Agregar Materias</h2>
    <form action="Asignar_Materias.php" method="post">
        <label for="formacion">Seleccione una formación:</label>
        <select name="formacion" id="formacion" required>
            <option value="" disabled selected>Seleccione una formación</option>
            <!-- Aquí se cargarán dinámicamente las formaciones desde PHP -->
            <?php
            // Incluir el archivo de funciones
            include('../funciones.php');

            // Obtener la lista de formaciones
            $formaciones = obtenerListaFormaciones();

            // Mostrar opciones de formaciones
            foreach ($formaciones as $formacion) {
                echo '<option value="' . $formacion['Clave'] . '">' . $formacion['Nombre'] . '</option>';
            }
            ?>
        </select><br><br>
        <input type="submit" value="Seleccionar">
    </form>
    <br>
    <a href="IndexAdmin.php">Volver al Inicio</a>
</body>
</html>
