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

// Realizar la consulta para obtener los ciclos escolares
include('../funciones.php');
$conexion = conectarDB();
$consultaCiclos = obtenerCiclosEscolares();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Kardex Escolar</title>
    <link rel="stylesheet" href="../css/csssecretaria.css"> 
</head>

<body>
    <nav>
        <ul>
            <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="ElegirKardex.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Generador de Kardex Escolar</h1>
    </nav>
    <div class="contenedor2">
        <p>Instrucciones: Ingrese matrícula y ciclo, luego haga clic en 'Entrar' para consultar calificaciones.</p>
        <div class="parte3">
            <form action="VerCalificacionesAlumnos.php" method="post">
                <label for="numero">Matricula:</label>
                <input type="number" class="caja" id="matricula" name="matricula" required><br>
                <label for="ciclo_escolar">Ciclo Escolar:</label>
                <select id="ciclo_escolar" class="caja" name="ciclo_escolar" required>
                    <?php
                    while ($row = $consultaCiclos->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['Ciclo_Escolar']}'>{$row['Ciclo_Escolar']}</option>";
                    }
                    ?>
                </select><br>
                <button type="submit" class="Boton">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>
