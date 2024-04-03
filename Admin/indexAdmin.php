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
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
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
            <li><a href="LogoutAdmin.php"><img src="../iconos//cerrarsesion.png" width="20px"><br>Cerrar sesión</a></li>
            <li><a href="#"><img src="../iconos//ciclo.png" width="20px"><br><?php echo "Ciclo:" . $registros['Ciclo_Activo']; ?></a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80"> Sistema de Control Escolar FCQ</h1>
    </nav>
    <h2>Bienvenido, <?php echo $_SESSION['admin_usuario']; ?>!</h2>

    <div class="contenedor">
        <div class="parte">Editar Admin<br><br>
            <div class="parte2"><a href="EditarAdmin.php"><img src="../iconos/editar_admin.png" width="100"></a></div>
        </div>
        <div class="parte">Eliminar Admin<br><br>
            <div class="parte2"><a href="eliminarAdmin.php"><img src="../iconos/eliminar_admin.png" width="100"></a></div>
        </div>
        <div class="parte">Agregar Secretaria<br><br>
            <div class="parte2"><a href="AgregarSecretaria.php"><img src="../iconos/secre.png" width="100"></a></div>
        </div>
        <div class="parte">Editar Secretaria<br><br>
            <div class="parte2"><a href="EditarSecretarias.php"><img src="../iconos/editar_secre.png" width="100"></a></div>
        </div>
        <div class="parte">Eliminar Secretaria<br><br>
            <div class="parte2"><a href="eliminar_secretaria.php"><img src="../iconos/eliminar_secretaria.png" width="100"></a></div>
        </div>
        <div class="parte">Agregar Profesor<br><br>
            <div class="parte2"><a href="Agregarprofesor.php"><img src="../iconos/profesor_add.png" width="100"></a></div>
        </div>
        <div class="parte">Editar Profesor<br><br>
            <div class="parte2"><a href="EditarProfesores.php"><img src="../iconos/editprof.png" width="100"></a></div>
        </div>
        <div class="parte">Eliminar Profesor<br><br>
            <div class="parte2"><a href="EliminarProfesores.php"><img src="../iconos/deleteprof.png" width="100"></a></div>
        </div>
        <div class="parte">Agregar ciclo <br><br>
            <div class="parte2"><a href="Agregaciclo.php"><img src="../iconos/ciclo.png" width="100"></a></div>
        </div>
        <div class="parte">Gestionar ciclo activo <br><br>
            <div class="parte2"><a href="Cicloactivo.php"><img src="../iconos/deleteprof.png" width="100"></a></div>
        </div>
        <div class="parte">Agregar Materias<br>
            <div class="parte2"><a href="AgregarMaterias.php"><img src="../iconos/AñadirMaterias.png" width="100"></a></div>
        </div>
        <div class="parte">Materias profesores<br>
            <div class="parte2"><a href="materiasprofesores.php"><img src="../iconos/Vermaterias.png" width="100"></a></div>
        </div>

        <div class="parte">Contraseñas  Secretarias<br>
            <div class="parte2"><a href="contraseñassecretarias.php"><img src="../iconos/VerSecres.png" width="100"></a></div>
        </div>
        <div class="parte">Contraseñas  Profesores<br>
            <div class="parte2"><a href="contraseñaprofesores.php"><img src="../iconos/Contraprof.png" width="100"></a></div>
        </div>
        <div class="parte">Contraseñas  Administrador<br>
            <div class="parte2"><a href="contraseñaadmins.php"><img src="../iconos/VerAdmin.png" width="100"></a></div>
        </div>
        
    </div>
</body>

</html>
