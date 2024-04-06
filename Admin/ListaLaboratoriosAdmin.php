<?php
session_start();

// Verificar la sesión del administrador
if (!isset($_SESSION['admin_usuario']) || empty($_SESSION['admin_usuario'])) {
    header("Location: LoginAdmin.php");
    exit();
}

// Verificar el tiempo de inactividad y cerrar sesión si es necesario
$inactivity_timeout = 1800; // 30 minutos (en segundos)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactivity_timeout)) {
    session_unset();
    session_destroy();
    header("Location: LoginAdmin.php");
    exit();
}

// Actualizar el tiempo de actividad
$_SESSION['last_activity'] = time();

// Consultar laboratorios
include('../funciones.php');
$registros = consultarLaboratorios();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Laboratorios</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>

<body>
    <nav>
        <ul>
            <li><a href="indexAdmin.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="indexAdmin.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Lista de Laboratorios</h1>
    </nav>
    <div class="contenedor2">
        <div class="tablas">
            <table>
                <tr>
                    <td>Numero de Laboratorio</td>
                    <td>Nombre</td>
                    <td>Encargado</td>
                </tr>

                <?php
                foreach ($registros as $value) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($value["IdLaboratorios"]) . "</td>";
                    echo "<td>" . htmlspecialchars($value["NomLaboratorio"]) . "</td>";
                    echo "<td>" . htmlspecialchars($value["NombreProfesor"]) . " " . htmlspecialchars($value["ApellidosProfesor"]) . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>

            <!-- Formulario para generar PDF -->
            <form action="PdfLaboratorio.php" method="POST">
                <input type="hidden" name="datos_laboratorio" value='<?php echo json_encode($registros); ?>'>
                <button type="submit" name="generar_pdf">Generar PDF</button>
            </form>
        </div>
    </div>
</body>

</html>
