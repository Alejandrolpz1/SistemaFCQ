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
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Lista alumnos</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">

    <script>
        // Utiliza el historial del navegador para reemplazar la URL actual con la de SeleccionarCarreraXSemestre.php
        window.history.replaceState({}, document.title, "alumnosEgresados.php");
    </script>
</head>

<body>
    <nav>
        <ul>
            <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="alumnosEgresados.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Registro de Estudiantes Egresados</h1>
    </nav>

    <?php
    // Verificar si se ha enviado el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include('../funciones.php');
        $carrera = isset($_POST['carrera']) ? filter_var($_POST['carrera'], FILTER_SANITIZE_STRING) : '';
    } else {
        include('../funciones.php');
        $carrera = isset($_GET['carrera']) ? filter_var($_GET['carrera'], FILTER_SANITIZE_STRING) : '';
    }
    //echo "<td>" . htmlspecialchars($value["Matricula"]) . "</td>";
    $resultadoCarrera = obtenerCarreraid($carrera);
    ?>
<div class="contenedor2">
        <p>Lista de estudiantes egresados de <?php echo $resultadoCarrera['Nombre']; ?> </p>
        <div class="tablas">
        <form action="ConfirmacionEliminaregersados.php" method="POST">
        
        <input type="hidden" id="carrera" name="carrera" value="<?php echo $carrera; ?>" required><br><br>

        <input type="submit" class = "Boton2" value="Eliminar Todos">
    </form>
        <div class="tablas">
            <table>
                <tr>
                    <td>Matricula</td>
                    <td>Nombre</td>
                    <td>Apellidos</td>
                    <td>Grupo</td>
                    <td>Semestre</td>
                    <td>Eliminar</td>
                </tr>
                <?php

                $alumnos1 = obteneralumnosegersados($carrera);

                foreach ($alumnos1 as $value) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($value["Matricula"]) . "</td>";
                    echo "<td>" . htmlspecialchars($value["Nombre"]) . "</td>";
                    echo "<td>" . htmlspecialchars($value["Apellido"]) . "</td>";
                    echo "<td>" . htmlspecialchars($value["Grupo"]) . "</td>";
                    echo "<td>" . htmlspecialchars($value["Semestre"]) . "</td>";
                    echo "<td>";
                    echo "<a href='ConfirmacionEliminarEgresado.php?Matricula_Alumno=" . htmlspecialchars($value['Matricula']) . "&Semestre=" . htmlspecialchars($value['Semestre']) . "&Grupo=" . htmlspecialchars($value['Grupo']) ."&Carrera=" . htmlspecialchars($carrera) . "'>";
                    echo "<img src='../iconos/eliminar.png' width='32' height='32'></a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

    </div>

</body>

</html>