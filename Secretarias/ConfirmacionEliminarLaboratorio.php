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
    <title>confirmacion eliminar Laboratorio</title> 
    <link rel="stylesheet" href="../css/csssecretaria.css"> 
</head>
<body>
    <nav>
        <ul>
            <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="ListaLaboratorios.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Eliminar Laboratorio</h1>
    </nav>
    <?php
    $idlab = isset($_GET['IdLaboratorios']) ? filter_var($_GET['IdLaboratorios'], FILTER_SANITIZE_STRING) : '';
    include('../funciones.php');
    ?>
    <div class="contenedor2">
        <div class="parte3">
            <h4>¿Estás seguro de eliminar el laboratorio?</h4>
            <a href="ListaLaboratorios.php"><button class="Boton">Regresar</button></a>
            <form method="post" action="EliminarLaboratorio.php">
                <input type="hidden" name="idlab" value="<?php echo $idlab;?>">
                <input class="Boton" type="submit" value="Eliminar">
            </form>
        </div>
    </div>
</body>
</html>

