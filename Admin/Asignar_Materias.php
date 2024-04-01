<?php
session_start();


// Verificar la sesión del administrador
if (!isset($_SESSION['admin_usuario']) || empty($_SESSION['admin_usuario'])) {
    header("Location: LoginAdmin.php");
    exit();
}

include 'funciones.php';
// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID de la formación seleccionada
    $formacionClave = $_POST['formacion'];

    // Obtener la lista de materias para la formación seleccionada
    $materias = obtenerMateriasPorFormacion($formacionClave);

    // Obtener la lista de profesores
    $profesores = obtenerListaProfesores();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Materias</title>
</head>
<body>
    <h2>Asignar Materias</h2>
    <form action="procesar_asignacion.php" method="post">
        <input type="hidden" name="formacion" value="<?php echo $formacionClave; ?>">
        <label for="profesor">Seleccione un profesor:</label>
        <select name="profesor" id="profesor" required>
            <option value="" disabled selected>Seleccione un profesor</option>
            <!-- Aquí se cargarán dinámicamente los profesores desde PHP -->
            <?php foreach ($profesores as $profesor) : ?>
                <option value="<?php echo $profesor['NumEmp']; ?>"><?php echo $profesor['Nombre'] . ' ' . $profesor['Apellidos']; ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <label>Materias:</label><br>
        <!-- Aquí se cargarán dinámicamente las materias desde PHP -->
        <?php foreach ($materias as $materia) : ?>
            <input type="checkbox" name="materias[]" value="<?php echo $materia['Clave']; ?>"> <?php echo $materia['Nombre']; ?><br>
        <?php endforeach; ?><br>
        <input type="submit" value="Asignar Materias">
    </form>
    <br>
    <a href="AgregarMaterias.php">Volver</a>
</body>
</html>
