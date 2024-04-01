

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
    <title>Lista Laboratorios</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>

<body>
    <nav>
        <ul>
            <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="IndexSecretarias.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
            <li><a href="Insertarlaboratorio.php"><img src="../iconos//AgreagarLaboratorios.png" width="25px"><br>Agregar Laboratorios</a></li>
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
                    <td>Editar</td>
                    <td>Eliminar</td>
                </tr>

                <?php
                // Verificar si se ha enviado el formulario
                include('../funciones.php');
                // Consultar laboratorios
                $registros = consultarLaboratorios();

                foreach ($registros as $value) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($value["IdLaboratorios"]) . "</td>";
                    echo "<td>" . htmlspecialchars($value["NomLaboratorio"]) . "</td>";
                    echo "<td>" . htmlspecialchars($value["NombreProfesor"]) . " " . htmlspecialchars($value["ApellidosProfesor"]) . "</td>";
                    echo "<td>";
                    echo "<a href='EditarLaboratorio.php?IdLaboratorios=" . htmlspecialchars($value['IdLaboratorios']) . "'>";
                    echo "<img src='../iconos/editar.png' width='32' height='32'></a>";
                    echo "</td>";
                    echo "<td>";
                    echo "<a href='ConfirmacionEliminarLaboratorio.php?IdLaboratorios=" . htmlspecialchars($value['IdLaboratorios']) . "'>";
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

