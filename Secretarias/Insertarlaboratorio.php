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

include('../funciones.php');

$listamaterias = consultarmaterias();
$ProfesoresDisponibles = obtenerListaProfesores();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Laboratorio</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="ListaLaboratorios.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
            <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Insertar Laboratorios</h1>
        </ul>
    </nav>
    <div class="contenedor2">
    <div class="parteu">
    <form method="POST" action="">
            <label for="IdLaboratorios">Número de Lab:</label><br>
            <input type="number" class = "caja" id="IdLaboratorios" name="IdLaboratorios"  required><br><br>
            <label for="nombre">Nombre de Lab:</label><br>
            <input type="text" class = "caja" id="nombre" name="nombre" required><br>
            <label for="jefe">Encargado:</label><br>
        
            <select class="caja"  id="Nombredeljefe" name="Nombredeljefe" required>
                <label for="Nombredeljefe">Encargado:</label>
                <?php foreach($ProfesoresDisponibles as $profesores): ?>
                    <option value="<?php echo $profesores['Nombre']. ' ' . $profesores['Apellidos']; ?>" data-nombre-apellido="<?php echo $profesores['NumEmp'] ; ?>"><?php echo $profesores['Nombre']. ' ' . $profesores['Apellidos']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" id="jefe" name="jefe" readonly><br>
            <input type="submit" class = "Boton" value="Enviar">
        </form>
        </div>
    </div>
</body>
<script src="jsSincronizarNombreMatr.js"></script>
</html>

<?php
// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario de login y filtrar
    $idLab = isset($_POST['IdLaboratorios']) ? filter_var($_POST['IdLaboratorios'], FILTER_SANITIZE_STRING) : '';
    $nombreLab = isset($_POST['nombre']) ? filter_var($_POST['nombre'], FILTER_SANITIZE_STRING) : '';
    $jefeLab = isset($_POST['jefe']) ? filter_var($_POST['jefe'], FILTER_SANITIZE_STRING) : '';
    $mensaje = insertarLaboratorios($idLab, $nombreLab, $jefeLab);
    echo $mensaje;
}
?>
