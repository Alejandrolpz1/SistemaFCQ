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
    <title>Editar Laboratorio</title>
    <link rel="stylesheet" href="../css/csssecretaria.css">
</head>

<body>
    <nav>
        <ul>
            <li><a href="IndexSecretarias.php"><img src="../iconos//homelogo.png" width="20px"><br>Home</a></li>
            <li><a href="ListaLaboratorios.php"><img src="../iconos//back.png" width="20px"><br>Atras</a></li>
        </ul>
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">Editar Laboratorio</h1>
    </nav>
    <?php 
        $idLaboratorio = htmlspecialchars($_GET['IdLaboratorios']);
        include('../funciones.php');
        $registros = consultarLaboratoriosWhereMatricula($idLaboratorio);
        
        $ProfesoresDisponibles = obtenerListaProfesores();
        $nombreprofesorid = $registros['JefeNumEmp'];
        $nombreprofesor = obtenerNombreProfesor2($nombreprofesorid);
        $nombreCompleto = $nombreprofesor['Nombre'] . ' ' . $nombreprofesor['Apellidos'];
    ?>
    <div class="contenedor2">
        <div class="parteu">
            <form method="POST" action="ActualizarLabotratorio.php">
                <label for="idlab">Número de Laboratorio</label><br>
                <input type="number" class="caja" id="idlab" name="idlab" value="<?php echo $registros['IdLaboratorios']; ?>" placeholder="Ingrese nuevo numero" required>
                
                <br><label for="nombre">Nombre Laboratorio:</label><br>
                <input class="caja" type="text" id="nombre" name="nombre" value="<?php echo $registros['NomLaboratorio'];?>" placeholder="Ingrese nuevo nombre" required>
                
                <br><label for="Encargado">Encargado:</label><br>
                <select class="caja" id="Nombredeljefe" name="Nombredeljefe" required>
                    <label for="Nombredeljefe">Numero de Empleado del Encargado:</label>
                    <?php foreach ($ProfesoresDisponibles as $profesor): ?>
                        <?php $nombreApellido = $profesor['Nombre'] . ' ' . $profesor['Apellidos']; ?>
                        <option value="<?php echo $nombreApellido; ?>" data-nombre-apellido="<?php echo $profesor['NumEmp']; ?>" <?php echo ($nombreCompleto == $nombreApellido) ? 'selected' : ''; ?>>
                            <?php echo $nombreApellido; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" id="jefe" name="jefe" placeholder="Ingrese nuevo Enacargado" required>
                <input type="submit" class="Boton" value="Editar">
            </form>
        </div>
    </div>
    <script src="jsSincronizarNombreMatr.js"></script>
</body>

</html>
