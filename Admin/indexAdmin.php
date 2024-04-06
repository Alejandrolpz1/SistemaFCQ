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
    <h2>Bienvenido, <?php echo isset($_SESSION['admin_nombre']) ? $_SESSION['admin_nombre'] : ''; ?>!</h2>

    <div class="contenedor">
    <div class="parte">Cerrar Sesión<br><br>
            <div class="parte4">
                <a href="LogoutAdmin.php">
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
        <div class="parte">Editar Admin<br><br>
            <div class="parte2"><a href="EditarAdmin.php"><img src="../iconos/editar_admin.png" width="100"></a></div>
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

        <div class="parte">Gestionar ciclo activo <br><br>
            <div class="parte2"><a href="Cicloactivo.php"><img src="../iconos/ciclo.png" width="100"></a></div>
        </div>

        <div class="parte">Egresados<br>
            <div class="parte2"><a href="Egresados.php"><img src="../iconos/graduado.png" width="100"></a></div>
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
       

        <div class="parte">Agregar ciclo <br><br>
            <div class="parte2"><a href="Agregaciclo.php"><img src="../iconos/ciclo.png" width="100"></a></div>
        </div>

        <div class="parte">Laboratorios <br><br>
            <div class="parte2"><a href="ListaLaboratoriosAdmin.php"><img src="../iconos/laboratorios.png" width="100"></a></div>
        </div>


        </div>
      
        

    
        
    </div>
    <footer>
        <a><img src="../iconos/UABJOEscudo.png" width="60"></a>
        <p><br>Sistema de Control Escolar de la Facultad de Ciencias Químicas de la U.A.B.J.O. </p>
    </footer>
</body>

</html>
