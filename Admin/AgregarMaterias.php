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
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>
<body>
<nav>
    <ul>
        <li><a href="IndexAdmin.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
        <li><a href="IndexAdmin.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
    </ul>
    <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Agregar Materias</h1>
</nav>

<div class="contenedor2"> <div class="parteu"><h2>Agregar Materias</h2>
    <form action="Asignar_Materias.php" method="post">
        <label for="formacion">Seleccione una formación:</label><br><br>
        <select name="formacion"  class="caja" id="formacion" required>
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
        </select>
        <input type="submit"  class="Boton" value="Seleccionar">
    </form>

    <a href="IndexAdmin.php"><button type="button" class="Boton2">Volver Inicio</button></a></div></div>
    
</body>
</html>
