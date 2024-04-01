<?php

session_start();
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
 header("Location: LoginSecretarias.php");
    exit();
}

$inactive_time = 1800; 

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive_time)) {
    // Si ha pasado demasiado tiempo, cerrar sesión
    session_unset();
    session_destroy();
    header("Location: LoginSecretarias.php");
    exit();
}

// Actualizar la marca de tiempo de la última actividad
$_SESSION['last_activity'] = time();

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
            <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="SeleccionarCarreraXSemestre.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80"> Asignación de Materias</h1>
    </nav>

    <?php 
    $matriculalumno = isset($_GET['Matricula_Alumno']) ? filter_var($_GET['Matricula_Alumno'], FILTER_SANITIZE_STRING) : '';
    $carrera = isset($_GET['Clave']) ? filter_var($_GET['Clave'], FILTER_SANITIZE_STRING) : '';
    $semestre = isset($_GET['Semestre']) ? filter_var($_GET['Semestre'], FILTER_SANITIZE_STRING) : '';
    $grupo = isset($_GET['Grupo']) ? filter_var($_GET['Grupo'], FILTER_SANITIZE_STRING) : '';
    include '../funciones.php';
    $alumno = consultarAlumnosWhereMatricula($matriculalumno);
    $cicloActivo = ObtenerCicloActivo();
    // Obtener materias recomendadas y disponibles
    $consulta = MateriasRecomendadas($carrera, $semestre);
    $materiasDisponibles = ObtenerMateriasFaltantes($carrera, $semestre);
    ?>
    <div class = "contenedor2">
        <?php echo "Asignación de Materias para {$alumno['Nombre']} {$alumno['Apellido']} para el ciclo {$cicloActivo['Ciclo_Activo']}";
    echo "<br>Semestre Actual: {$alumno['Semestre']}"; ?>
    </div>

    <div class = "contenedor">
    
        <div class = "parte">
            <h4>Materias Recomendables Para <?php echo "{$alumno['Semestre']}"; ?> Semestre</h4><br>

            <select class="caja" id="materiasRecomendables">
                <?php foreach($consulta as $materia): ?>
                <option value="<?php echo $materia['Nombre']; ?>"><?php echo $materia['Nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            <br><button class="Boton2" onclick="agregarMateria('materiasRecomendables')">Agregar</button>
        </div>
        <div class = "parte">
            <h4>Asignaturas elegidas para <?php echo "{$alumno['Nombre']} {$alumno['Apellido']} "; ?></h4><br>
            <form action="#" method="post">
                
                <label for="materia1">Materia1:</label>
                <input type="text" class="caja" name="materia1" id="materia1"  required><br>

                <label for="materia2">Materia2:</label>
                <input type="text" class="caja" name="materia2" id="materia2" readonly><br>

                <label for="materia3">Materia3:</label>
                <input type="text" class="caja" name="materia3" id="materia3" readonly><br>

                <label for="materia4">Materia4:</label>
                <input type="text" class="caja" name="materia4" id="materia4" readonly><br>

                <label for="materia5">Materia5:</label>
                <input type="text" class="caja" name="materia5" id="materia5" readonly><br>

                <label for="materia6">Materia6:</label>
                <input type="text" class="caja" name="materia6" id="materia6" readonly><br>

                <label for="materia7">Materia7:</label>
                <input type="text" class="caja" name="materia7" id="materia7" readonly><br>

                <label for="materia8">Materia8:</label>
                <input type="text" class="caja" name="materia8" id="materia8" readonly><br>

                <label for="materia9">Materia9:</label>
                <input type="text" class="caja" name="materia9" id="materia9" readonly><br>

                <label for="materia10">Materia10:</label>
                <input type="text" class="caja" name="materia10" id="materia10" readonly><br>

                <input type="submit" class= "Boton" value="Enviar">


            </form>
            <a href="http://localhost/SistemaFCQ/Secretarias/AsignarMaterias.php?Matricula_Alumno=<?php echo "{$alumno['Matricula']}"; ?>&Semestre=<?php echo "{$alumno['Semestre']}"; ?>&Grupo=A&Clave=<?php echo "{$alumno['formacion']}"; ?>"><button class = "Boton2">Deshacer</button></a>
        </div>
        <div class = "parte">
            <h4>Materias Disponibles</h4><br>
            <p>En caso de no localizar la materia que el estudiante planea solicitar, le invitamos a realizar la búsqueda correspondiente aquí.</p>
            <br>
            <select class="caja" id="materiasDisponibles">
                <label for="busqueda">Buscar materia:</label>
                <?php foreach($materiasDisponibles as $materia): ?>
                <option value="<?php echo $materia['Nombre']; ?>"><?php echo $materia['Nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            <br><button class="Boton2" onclick="agregarMateria('materiasDisponibles')">Agregar</button>

        </div>

    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos del formulario y filtrar
        $materia1 = isset($_POST['materia1']) ? filter_var($_POST['materia1'], FILTER_SANITIZE_STRING) : '';
        $materia2 = isset($_POST['materia2']) ? filter_var($_POST['materia2'], FILTER_SANITIZE_STRING) : '';
        $materia3 = isset($_POST['materia3']) ? filter_var($_POST['materia3'], FILTER_SANITIZE_STRING) : '';
        $materia4 = isset($_POST['materia4']) ? filter_var($_POST['materia4'], FILTER_SANITIZE_STRING) : '';
        $materia5 = isset($_POST['materia5']) ? filter_var($_POST['materia5'], FILTER_SANITIZE_STRING) : '';
        $materia6 = isset($_POST['materia6']) ? filter_var($_POST['materia6'], FILTER_SANITIZE_STRING) : '';
        $materia7 = isset($_POST['materia7']) ? filter_var($_POST['materia7'], FILTER_SANITIZE_STRING) : '';
        $materia8 = isset($_POST['materia8']) ? filter_var($_POST['materia8'], FILTER_SANITIZE_STRING) : '';
        $materia9 = isset($_POST['materia9']) ? filter_var($_POST['materia9'], FILTER_SANITIZE_STRING) : '';
        $materia10 = isset($_POST['materia10']) ? filter_var($_POST['materia10'], FILTER_SANITIZE_STRING) : '';
        $mensaje = insertarAsignacionMaterias($matriculalumno,$carrera,$semestre,$grupo,$materia1,$materia2,$materia3,$materia4,$materia5,$materia6,$materia7,$materia8,$materia9,$materia10);
        echo $mensaje; 
    }
    ?>

    <script>
        function agregarMateria(selectId) {
            var select = document.getElementById(selectId);
            var selectedOption = select.options[select.selectedIndex];
            var materiaInput = document.getElementById('materia1'); // Empezamos desde la materia1

            // Buscar la primera caja de texto vacía y agregar la materia seleccionada
            for (var i = 1; i <= 10; i++) {
                var input = document.getElementById('materia' + i);
                if (input.value === '') {
                    input.value = selectedOption.value;
                    select.remove(select.selectedIndex); // Eliminar la opción seleccionada
                    break;
                }
            }
        }
    </script>
</body>

</html>