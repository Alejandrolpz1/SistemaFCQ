<?php
session_start();

// Verificar la sesión del administrador
if (!isset($_SESSION['admin_usuario']) || empty($_SESSION['admin_usuario'])) {
    header("Location: LoginAdmin.php");
    exit();
}
include('../funciones.php');

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID de la formación seleccionada
    $formacionClave = $_POST['formacion'];

    // Obtener la lista de materias para la formación seleccionada
    $materias = obtenerMateriasPorFormacion($formacionClave);

    // Obtener la lista de profesores
    $profesores = obtenerListaProfesores();

    // Obtener la lista de laboratorios
    $laboratorios = consultarLaboratorios1();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Materias</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>
<body>
<nav>
    <ul>
        <li><a href="IndexAdmin.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
        <li><a href="AgregarMaterias.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
    </ul>
    <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Asignar Materias</h1>
</nav>
<div class="contenedor2"> <div class = "parteu"><h2>Asignar Materias</h2>
    <form action="procesar_asignacion.php" method="post">
        <input type="hidden" name="formacion" value="<?php echo $formacionClave; ?>">
        <label for="profesor">Seleccione un profesor:</label>
        <select name="profesor"  class="caja" id="profesor" required>
            <option value="" disabled selected>Seleccione un profesor</option>
            <!-- Aquí se cargarán dinámicamente los profesores desde PHP -->
            <?php foreach ($profesores as $profesor) : ?>
                <option value="<?php echo $profesor['NumEmp']; ?>"><?php echo $profesor['Nombre'] . ' ' . $profesor['Apellidos']; ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <label for="laboratorio">Seleccione un laboratorio:</label>
        <select name="laboratorio" class="caja" id="laboratorio" required>
            <option value="" disabled selected>Seleccione un laboratorio</option>
            <!-- Aquí se cargarán dinámicamente los laboratorios desde PHP -->
            <?php foreach ($laboratorios as $laboratorio) : ?>
                <option value="<?php echo $laboratorio['IdLaboratorios']; ?>"><?php echo $laboratorio['NomLaboratorio']; ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <label>Materias:</label><br>
        <!-- Aquí se cargarán dinámicamente las materias desde PHP -->
        <?php foreach ($materias as $materia) : ?>
            <label class="container">
                <input type="checkbox" name="materias[]" value="<?php echo $materia['Clave']; ?>"> <?php echo $materia['Nombre']; ?><br>
                <div class="checkmark"></div>
            </label>
        <?php endforeach; ?><br>
        <input type="submit" class = "Boton" value="Asignar Materias">
    </form>
    <br>
    <a href="AgregarMaterias.php"><button type="button" class="Boton2">Cancelar</button></a></div> </div>
</body>
</html>
