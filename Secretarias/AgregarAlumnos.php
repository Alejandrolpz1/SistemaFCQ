

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
    <title>Ingresando Alumnos</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
    <script>
    // Utiliza el historial del navegador para reemplazar la URL actual con la de SeleccionarCarreraXSemestre.php
    window.history.replaceState({}, document.title, "PanelAlumnos.php");
</script>
</head>

<body>
    <nav>
        <ul>
            <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="PanelAlumnos.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Ingresar Estudiante</h1>
    </nav>
    <div class="contenedor2">
    <p>Complete todos los campos y envíe el formulario.</p><br>
        <div class="parteu">
        <br>
            <form method="POST" action="AgregarAlumnos.php">
                <h3>Ingreso de Estudiantes</h3><br>
                <label for="matricula">Matrícula:</label>
                <input type="number" class ="caja" id="matricula" name="matricula" min="100000" max="999999" required>

                <label for="nombre">Nombre :ㅤ</label>
                <input type="text" class ="caja" id="nombre" name="nombre" required>

                <label for="apellidos">Apellidos:</label>
                <input type="text" class ="caja" id="apellidos" name="apellidos" required> <br>
                <br><label for="sexo">Sexo : </label>
                <input type="radio" id="sexo1_femenino" name="sexo1" value="Femenino"> Mujer
                <input type="radio" id="sexo1_masculino" name="sexo1" value="Masculino" required> Hombre

                <br><br><label for="telefono">Teléfono:</label>
                <input type="number" class ="caja" id="telefono" name="telefono" required>

                <label for="correo">Correo:ㅤ</label>
                <input type="email" class ="caja" id="correo" name="correo" required> <br>

                <?php
                include '../funciones.php';
                $CarrerasDisponibles = obtenerCarrerasygrupos();
                ?>
                <br><label for="carrera">Nivel Educativo:</label>
                <select class="carrera" id="carrera" name="carrera" required>
                    <label for="carrera">Clave Carrera:</label>
                    <?php foreach($CarrerasDisponibles as $carrera): ?>
                    <option value="<?php echo $carrera['Nombre']; ?>" data-nombre-carrera="<?php echo $carrera['Clave'] ; ?>"><?php echo $carrera['Nombre']; ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="hidden" id="carreraclave" name="carreraclave" readonly>

                <br><br><label for="grupo">Grupo : ㅤ</label>
                <select name="opciones" class ="caja">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="F">F</option>
                    <option value="G">G</option>
                    <option value="H">H</option>
                    <option value="U">U</option>
                </select>

                <label for="semestre">Semestre: </label>
                <input type="number" class ="caja" id="semestre" name="semestre" min="1" max="12" required><br><br>
                <input type="submit"  class = "Boton" value="Enviar">
            </form>

        </div>
       
    </div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var selectcarrera = document.getElementById('carrera');
        var inputNombreJefe = document.getElementById('carreraclave');

        selectcarrera.addEventListener('change', function() {
            var selectedOption = selectcarrera.options[selectcarrera.selectedIndex];
            var nombrecarrera = selectedOption.getAttribute('data-nombre-carrera');
            carreraclave.value = nombrecarrera;
        });

        // Para mostrar carrera seleccionado inicialmente
        var selectedOption = selectcarrera.options[selectcarrera.selectedIndex];
        var nombrecarrera = selectedOption.getAttribute('data-nombre-carrera');
        carreraclave.value = nombrecarrera;
    });
</script>

<?php
// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario de login y filtrar
    $matricula = isset($_POST['matricula']) ? filter_var($_POST['matricula'], FILTER_SANITIZE_STRING) : '';
    $nombre = isset($_POST['nombre']) ? filter_var($_POST['nombre'], FILTER_SANITIZE_STRING) : '';
    $apellidos = isset($_POST['apellidos']) ? filter_var($_POST['apellidos'], FILTER_SANITIZE_STRING) : '';
    $telefono = isset($_POST['telefono']) ? filter_var($_POST['telefono'], FILTER_SANITIZE_STRING) : '';
    $correo = isset($_POST['correo']) ? filter_var($_POST['correo'], FILTER_SANITIZE_STRING) : '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verificar que se haya enviado un valor válido desde el formulario
        if (isset($_POST["opciones"])) {
            $grupo = isset($_POST['opciones']) ? filter_var($_POST['opciones'], FILTER_SANITIZE_STRING) : '';
        } else {
            echo "No se ha seleccionado ninguna opción.";
        }
    }

    $semestre = isset($_POST['semestre']) ? filter_var($_POST['semestre'], FILTER_SANITIZE_STRING) : '';
    $carrera = isset($_POST['carreraclave']) ? filter_var($_POST['carreraclave'], FILTER_SANITIZE_STRING) : '';

    // Determinar qué opción se eligió en los radio
    if (isset($_REQUEST['sexo1'])) {
        if ($_REQUEST['sexo1'] == "Masculino") {
            $sexo = 'Hombre';
        } else if ($_REQUEST['sexo1'] == "Femenino") {
            $sexo = 'Mujer';
        }
    }

    
}




// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario de login y filtrar
    
        $matricula = isset($_POST['matricula']) ? filter_var($_POST['matricula'], FILTER_SANITIZE_STRING) : '';
        $nombre = isset($_POST['nombre']) ? filter_var($_POST['nombre'], FILTER_SANITIZE_STRING) : '';
		$apellidos = isset($_POST['apellidos']) ? filter_var($_POST['apellidos'], FILTER_SANITIZE_STRING) : '';
		$telefono = isset($_POST['telefono']) ? filter_var($_POST['telefono'], FILTER_SANITIZE_STRING) : '';
		$correo = isset($_POST['correo']) ? filter_var($_POST['correo'], FILTER_SANITIZE_STRING) : '';
        //$grupo = isset($_POST['grupo']) ? filter_var($_POST['grupo'], FILTER_SANITIZE_STRING) : '';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Verificar que se haya enviado un valor válido desde el formulario
            if (isset($_POST["opciones"])) {
                $grupo = isset($_POST['opciones']) ? filter_var($_POST['opciones'], FILTER_SANITIZE_STRING) : '';
            } else {
                echo "No se ha seleccionado ninguna opción.";
            }
        }
        $semestre =isset($_POST['semestre']) ? filter_var($_POST['semestre'], FILTER_SANITIZE_STRING) : '';

        $carrera=isset($_POST['carreraclave']) ? filter_var($_POST['carreraclave'], FILTER_SANITIZE_STRING) : '';
        $semestre =isset($_POST['semestre']) ? filter_var($_POST['semestre'], FILTER_SANITIZE_STRING) : '';
		//Determinar Que Opción Se Eligió En Los Radio
		
        if (isset($_REQUEST['sexo1'])) {
            if ($_REQUEST['sexo1'] == "Masculino") {
                $sexo = 'Hombre';
            } else if ($_REQUEST['sexo1'] == "Femenino") {
                $sexo = 'Mujer';
            }
        }       

        $mensaje = insertarAlumno($matricula, $nombre, $apellidos, $sexo, $telefono, $correo, $semestre, $grupo,$carrera);
        echo $mensaje;
   
}

?>

</html>