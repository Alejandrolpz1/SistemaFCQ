<?php
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: LoginProfesores.php");
    exit();
}

include('../funciones.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se han recibido los datos esperados
    if (isset($_POST['numEmpProfesor']) && isset($_POST['nombreMateria']) && isset($_POST['claveMateria'])) {
        // Recuperar los datos recibidos
        $numEmpProfesor = $_POST['numEmpProfesor'];
        $nombreMateria = $_POST['nombreMateria'];
        $claveMateria = $_POST['claveMateria'];

        // Obtener y mostrar los detalles de los alumnos que cursan esta materia
        $alumnosDetalles = obtenerDetallesAlumnosPorMateria($claveMateria);
        $grupos = array(); // Array para almacenar los grupos únicos
        echo"<nav>
            <ul>
                <li><a href='IndexProfesores.php'><img src='../iconos//homelogo.png' width='20px'><br>Home</a></li>
                <li><a href='IndexProfesores.php'><img src='../iconos//back.png' width='20px'><br>Cerrar sesión</a></li>
            </ul>
            <h1 id='tituloLaboratorio'><img src='../iconos/logoFCQ.png' width='80'> Sistema de Control Escolar FCQ</h1>
        </nav>";


        if ($alumnosDetalles) {
            echo "<div class = 'contenedor2'> ";
            echo "<h2>Detalles de la materia</h2>";
            echo "<p>Nombre de la materia: $nombreMateria</p>";
            echo "<h3>Seleccione el grupo:</h3>";
            echo "<form id='grupoForm' action='guardar_calificaciones.php' method='post'>"; // Formulario que envía los datos a guardar_calificaciones.php
            echo "<input type='hidden' name='numEmpProfesor' value='$numEmpProfesor'>";
            echo "<input type='hidden' name='claveMateria' value='$claveMateria'>";
            echo "<select id='grupoSelect'  class='caja' name='grupo' onchange='filtrarAlumnos()'>";
            foreach ($alumnosDetalles as $alumno) {
                // Almacenar grupos únicos en el array
                if (!in_array($alumno['Grupo'], $grupos)) {
                    $grupos[] = $alumno['Grupo'];
                    echo "<option value='{$alumno['Grupo']}'>{$alumno['Grupo']}</option>";
                }
            }
            echo "</select>";
            echo "<h3>Alumnos que cursan esta materia:</h3>";
            
            echo "<div class = 'tablas'> ";
            
            echo "<table id='alumnosTable'>";
            echo "<tr><th>Nombre del Alumno</th><th>Matrícula</th><th>Calificación</th></tr>";
            foreach ($alumnosDetalles as $alumno) {
                echo "<tr class='grupo{$alumno['Grupo']}'>"; // Agregar clase con el grupo para filtrar
                echo "<td>{$alumno['Nombre']} {$alumno['Apellido']}</td>";
                echo "<td>{$alumno['Matricula']}</td>";
                echo "<td><input type='number' name='calificaciones[{$alumno['Matricula']}]' value='" . (isset($alumno['Calificacion']) ? $alumno['Calificacion'] : '') . "' step='0.1'></td>";
                // Agregar un campo oculto para la matrícula del alumno
                echo "<input type='hidden'  name='matriculas[]' value='{$alumno['Matricula']}'>";
                echo "</tr>";

            }
            
            echo "</table>";
            echo "</div> ";
            echo "<input type='submit'  class='Boton' name='guardar' value='Guardar'>"; // Agregar un atributo 'name' al botón 'Guardar'
            echo "</form>";
        } else {
            echo "<p>No hay alumnos cursando esta materia actualmente.</p>";
        }
    } else {
        // Si no se reciben los datos esperados, redirigir
        header("Location: IndexProfesores.php");
        exit();
    }
} else {
    // Si no se envió la solicitud por método POST, redirigir
    header("Location: IndexProfesores.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Materias</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
    <script>
    function filtrarAlumnos() {
        var grupoSelect = document.getElementById("grupoSelect");
        var grupo = grupoSelect.value;
        var alumnosTable = document.getElementById("alumnosTable");
        var rows = alumnosTable.getElementsByTagName("tr");
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            if (row.classList.contains("grupo" + grupo)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        }
    }
    </script>
</head>
<body>
    <a href="IndexProfesores.php"><button type="button" class="Boton2">Volver</button></a>
    </div>
</body>
</html>
