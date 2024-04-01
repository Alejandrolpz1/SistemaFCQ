<?php
    //include('../funciones.php');
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
    <title>Gestion de Estudiantes</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Panel de Estudiantes</h1>
    </nav>
    <div class="contenedor">
    <div class="parte">Ingresar alumno<br><br>
            <div class="parte2"><a href="AgregarAlumnos.php"><img src="../iconos/ingresaralumno.png" width="100"></a></div>
        </div>
        <div class="parte">Gestion de Alumnos<br><br>
            <div class="parte2"><a href="SeleccionarCarreraXSemestre.php"><img src="../iconos/editar_alumno.png" width="100"></a></div>
        </div>
        <div class="parte">Calificaciones de Alumnos<br><br>
            <div class="parte2"><a href="ElegirKardex.php"><img src="../iconos/calificacionesalumno.png" width="100"></a></div>
        </div>
        <div class="parte">Editar Calificaciones<br><br>
        <div class="parte2"><a href="BuscarcalificacionesMaterias.php"><img src="../iconos/Editarcali.png" width="100"></a></div>
        </div>

        <div class="parte">Alumnos Baja<br><br>
        <div class="parte2"><a href="CarrerasBaja.php"><img src="../iconos/alumnosbaja.png" width="100"></a></div>
        </div>


        </div>
   

    <footer>
        <a><img src="../iconos/UABJOEscudo.png" width="60"  ></a>
        <p><br>Sistema de Control Escolar de la Facultad de Ciencias Químicas de la U.A.B.J.O. </p>
    </footer>

</body>
</html>
