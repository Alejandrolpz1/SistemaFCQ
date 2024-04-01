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
    <title>Selector de Carreras y Grupos</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>

<body>
    <nav>
        <ul>
            <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="PanelAlumnos.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Selector de Carreras y Grupos</h1>
    </nav>
    <div class="contenedor2">
        <p>Por favor, elija la carrera para ver a los estudiantes que han sido desvinculados de esta área de estudio.</p>
        <div class="parte3">
            <form action="ListaAlumnosBaja.php" method="post">
                <label for="carrera">Carrera:</label>

                <?php
                try {
                    // Utiliza la función conectarDB de funciones.php
                    include '../funciones.php'; // Asegúrate de incluir el archivo funciones.php
                    $conexion = conectarDB();

                    // Consulta para obtener las carreras
                    $consulta = $conexion->query("SELECT Clave, Nombre FROM formacion");
                    // $consulta_alumnos = $conexion->query("SELECT a.* FROM alumnos a INNER JOIN cursar c ON a.Matricula = c.Matricula_Alumno INNER JOIN materias m ON c.Clave_Materia = m.Clave INNER JOIN formacion f ON m.ClaveFormacion = f.Clave;");
                } catch (PDOException $e) {
                    echo "Error de conexión: " . $e->getMessage();
                } finally {
                    // No es necesario cerrar la conexión en este punto, ya que se hará automáticamente al final del script.
                }
                ?>

                <select class="caja" id="carrera" name="carrera" required>
                    <?php
                    while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['Clave']}'>{$row['Nombre']}</option>";
                    }
                    ?>
                </select><br>
                <input type="submit" class="Boton" value="Enviar">
            </form>
        </div>
    </div>

</body>

</html>