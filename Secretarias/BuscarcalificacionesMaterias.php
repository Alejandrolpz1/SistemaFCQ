
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
    <title>Editar Calificaciones</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>

<body>
    <nav>
        <ul>
            <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="PanelAlumnos.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80"> Selector de Carreras y Grupos</h1>
    </nav>
    <div class="contenedor2">
        <p>Selecciona carrera, semestre y grupo para ver y editar la lista de alumnos.</p><br>

        <form action="ListaMateriasCicloEditarCalificacion.php" method="post">
            <label for="carrera">Carrera:</label><br>
            <select id="carrera" class="caja2" name="carrera" required>
                <?php
                include '../funciones.php'; // Incluye el archivo de funciones
                obtenerformaciond();
                $consultaCiclos = obtenerCiclosEscolares();
                ?>
            </select><br><br>

            <select id="semestre" class="caja2" name="semestre" required></select>
            <select id="grupo" class="caja2" name="grupo" required></select><br><br>
            <label for="ciclo_escolar">Ciclo Escolar:</label>
                <select id="ciclo_escolar" class="caja" name="ciclo_escolar" required>
                    <?php
                    while ($row = $consultaCiclos->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['Ciclo_Escolar']}'>{$row['Ciclo_Escolar']}</option>";
                    }
                    ?>
                </select><br>

            <input type="submit" class="Boton" value="Enviar">
        </form>
    </div>

    <script>
    // Función para cargar grados y grupos automáticamente al cargar la página
    window.onload = function() {
        var carreraSelect = document.getElementById('carrera');
        var claveCarrera = carreraSelect.value; // Obtener el valor de la primera opción seleccionada
        cargarGradosGrupos(claveCarrera); // Llamar a la función para cargar grados y grupos
    };

    // Función para cargar grados y grupos mediante AJAX
    function cargarGradosGrupos(claveCarrera) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(this.responseText);
                var selectSemestre = document.getElementById('semestre');
                var selectGrupo = document.getElementById('grupo');
                selectSemestre.innerHTML = '';
                selectGrupo.innerHTML = '';

                // Llenar la lista de semestres
                data.semestres.forEach(function(semestre) {
                    var optionSemestre = document.createElement('option');
                    optionSemestre.value = semestre.Semestre;
                    optionSemestre.text = 'Semestre ' + semestre.Semestre;
                    selectSemestre.add(optionSemestre);
                });

                // Llenar la lista de grupos
                data.grupos.forEach(function(grupo) {
                    var optionGrupo = document.createElement('option');
                    optionGrupo.value = grupo.Grupo;
                    optionGrupo.text = 'Grupo ' + grupo.Grupo;
                    selectGrupo.add(optionGrupo);
                });
            }
        };
        xhttp.open("GET", "obtener_semestres_grupos.php?claveCarrera=" + claveCarrera, true);
        xhttp.send();
    }

    // Manejar el evento onchange del elemento select de carrera
    document.getElementById('carrera').onchange = function() {
        var claveCarrera = this.value;
        cargarGradosGrupos(claveCarrera);
    };
</script>
</body>
</html>