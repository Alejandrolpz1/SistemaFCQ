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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Página Principal de Secretarias</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>

<body>

    <?php 
    include('../funciones.php');
    $registros = ObtenerCicloActivo();
    ?>

    <nav>
        <ul>
            <li><a href="#"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="Logout.php"><img src="../iconos//cerrarsesion.png" width="20px"><br>Cerrar sesión</a></li>
            <li><a href="#"><img src="../iconos//ciclo.png" width="20px"><br><?php echo "Ciclo:" . $registros['Ciclo_Activo']; ?></a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80"> Sistema de Control Escolar FCQ</h1>
    </nav>

    <?php echo "<font color='black' face='Courier New' size=5>Hola $_SESSION[user_name] $_SESSION[user_apell]    </font>" ?>

    <br><br><br><br><br><br>

    <div class="contenedor">
        <div class="parte">Panel de Alumnos<br><br>
            <div class="parte2"><a href="PanelAlumnos.php"><img src="../iconos/alumno.png" width="100"></a></div>
        </div>
        <div class="parte">Laboratorios<br><br>
            <div class="parte2"><a href="ListaLaboratorios.php"><img src="../iconos/laboratorios.png" width="150"></a></div>
        </div>
        <div class="parte">Cerrar Sesión<br><br>
            <div class="parte4">
                <a href="Logout.php">
                    <button class="Btn">
                        <div class="sign">
                            <svg viewBox="0 0 512 512">
                                <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
                            </svg>
                        </div>
                        <div class="text">Logout</div>
                    </button>
                </a>
            </div>
        </div>
    </div>

    <br><br><br><br><br><br><br><br>

    <footer>
        <a><img src="../iconos/UABJOEscudo.png" width="60"></a>
        <p><br>Sistema de Control Escolar de la Facultad de Ciencias Químicas de la U.A.B.J.O. </p>
    </footer>

</body>

</html>
