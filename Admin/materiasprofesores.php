<?php
session_start();

include('../funciones.php');

// Verificar la sesión del administrador
if (!isset($_SESSION['admin_usuario']) || empty($_SESSION['admin_usuario'])) {
    header("Location: LoginAdmin.php");
    exit();
}

// Variables para almacenar el nombre del profesor
$nombreProfesor = '';
$materiasAsociadas = array();

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el número de empleado del profesor seleccionado
    $numEmp = $_POST['profesor'];

    // Obtener las materias asociadas al profesor
    $materiasAsociadas = obtenerMateriasAsociadas($numEmp);

    // Obtener el nombre del profesor
    $profesor = obtenerProfesorPorNumEmp($numEmp);
    $nombreProfesor = "{$profesor['Nombre']} {$profesor['Apellidos']}";

    // Verificar si se ha seleccionado una materia para eliminar
    if (isset($_POST['eliminar']) && !empty($_POST['eliminar'])) {
        $materiaAEliminar = $_POST['eliminar'];
        // Eliminar la materia asociada al profesor
        eliminarMateriaProfesor($numEmp, $materiaAEliminar);
        // Actualizar la lista de materias asociadas
        $materiasAsociadas = obtenerMateriasAsociadas($numEmp);
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materias de Profesor</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="IndexAdmin.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="IndexAdmin.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Materias de Profesor</h1>
    </nav>
    <div class="contenedor2">
        <h2>Materias de <?php echo $nombreProfesor; ?></h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="profesor">Seleccione un profesor:</label>
            <select name="profesor" class="caja" id="profesor" required>
                <option value="" disabled selected>Seleccione un profesor</option>
                <!-- Aquí puedes cargar dinámicamente los profesores -->
                <?php
                $profesores = obtenerListaProfesores();
                foreach ($profesores as $profesor) {
                    echo "<option value='{$profesor['NumEmp']}'>{$profesor['Nombre']} {$profesor['Apellidos']}</option>";
                }
                ?>
            </select><br><br>
            <input type="submit" class="Boton" value="Ver Materias">
        </form>
    </div>

    <?php if (!empty($materiasAsociadas)) : ?>
        <div class="contenedor2">
            <h3>Materias Asociadas:</h3>
            <label for="ciclo">Filtrar por Ciclo Escolar:</label>
            <select name="ciclo" class="caja" id="ciclo">
                <option value="todos">Todos los ciclos</option>
                <?php
                $ciclos = array_unique(array_column($materiasAsociadas, 'Ciclo_Escolar'));
                foreach ($ciclos as $ciclo) {
                    echo "<option value='{$ciclo}'>{$ciclo}</option>";
                }
                ?>
            </select>
            <ul id="materias-list">
                <?php foreach ($materiasAsociadas as $materia) : ?>
                    <li data-ciclo="<?php echo $materia['Ciclo_Escolar']; ?>">
                        <?php echo "{$materia['Materia']} ({$materia['Formacion']}) - Ciclo Escolar: {$materia['Ciclo_Escolar']}"; ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="display: inline;">
                            <input type="hidden" name="profesor" value="<?php echo $numEmp; ?>">
                            <input type="hidden" name="eliminar" value="<?php echo $materia['Id']; ?>">
                            <input type="submit" class="Boton4" value="Eliminar">
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <br>
    <div class="contenedor2">
        <a href="indexAdmin.php"><button type="button" class="Boton2">Volver</button></a>
    </div>

    <script>
        // Función para filtrar las materias por ciclo escolar
        document.getElementById('ciclo').addEventListener('change', function() {
            const selectedCiclo = this.value;
            const materiasList = document.getElementById('materias-list').getElementsByTagName('li');

            for (let i = 0; i < materiasList.length; i++) {
                const materia = materiasList[i];
                const cicloMateria = materia.getAttribute('data-ciclo');

                if (selectedCiclo === 'todos' || selectedCiclo === cicloMateria) {
                    materia.style.display = 'block';
                } else {
                    materia.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
