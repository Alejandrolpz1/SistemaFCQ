
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
    <title>Página Principal de Secretarias</title>
</head>
<body>
    <nav>
        <h1><img src="iconos/FCQ_logo.png" width="80">Ingresar Alumnos</h1>
        <ul>
            <li><a href="IndexSecretarias.php"><img src="iconos//homelogo.png" width="20px"><br>Home</a></li>
        </ul>
    </nav>
    <div id="contenedor">
    <form method="POST" action="">
        <label for="matricula">Matrícula:</label><br>
        <input type="number" id="matricula" name="matricula" min="1000000" max="9999999" required><br>
        <br>
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="apellidos">Apellidos:</label><br>
        <input type="text" id="apellidos" name="apellidos" required><br>
        <label for="sexo">Sexo:</label><br>
        <input type="radio" id="hombre" name="sexo" value="hombre" required>
        <label for="hombre">Hombre</label><br>
        <input type="radio" id="mujer" name="sexo" value="mujer" required>
        <label for="mujer">Mujer</label><br>
        <label for="telefono">Teléfono:</label><br>
        <input type="number" id="telefono" name="telefono" required><br>
        <label for="correo">Correo:</label><br>
        <input type="email" id="correo" name="correo" required><br>
        <label for="grupo">Grupo:</label><br>
        <input type="text" id="grupo" name="grupo" required><br>
        <label for="semestre">Semestre:</label><br>
        <input type="number" id="semestre" name="semestre" min="1" max="12" required><br>
        <input type="submit" value="Enviar">
    </form>
</div>


</body>
</html>
