<?php

include('../funciones.php');

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario de login y filtrar 
    $numEmp = isset($_POST['NumEmp']) ? filter_var($_POST['NumEmp'], FILTER_SANITIZE_STRING) : '';
    $password = isset($_POST['Password']) ? filter_var($_POST['Password'], FILTER_SANITIZE_STRING) : '';

    // Verificar que los campos no estén vacíos
    if (empty($numEmp) || empty($password)) {
        $mensaje_error = "Por favor, completa todos los campos.";
    } else {
        // Consultar el login
        $usuario_valido = consultarLoginSecretarias($numEmp, $password);

        if ($usuario_valido) {
            session_start();
            session_regenerate_id(); // Genera un nuevo ID de sesión para mayor seguridad
            $_SESSION = array(); // Limpia todas las variables de sesión

          
            $_SESSION['user_id'] = $usuario_valido['NumEmp'];
            $_SESSION['user_name'] = $usuario_valido['Nombre'];
            $_SESSION['user_apell'] = $usuario_valido['Apellido'];

            header("Location: IndexSecretarias.php");
            exit();
        } else {
            // Las credenciales son incorrectas, muestra un mensaje de error
            $mensaje_error = "Número de empleado o contraseña incorrectos";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/cssloging.css">
    <title>Login de Secretarias</title>
</head>
<body>
<nav>
    
        
        <div class = "contenedor3">
        <div class = "parteu">
        <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">  Sistema de Control Escolar FCQ <img src="../iconos/UABJOEscudo.png" width="75"></h1>
        </div></div></nav>

        <div class = "contenedor3">
        <div class = "parte">
        <?php if (isset($mensaje_error)): ?>
        <p style="color: red;"><?php echo $mensaje_error; ?></p>
    <?php endif; ?>
    <h2>Iniciar Sesión</h2><br>
            <form action="LoginSecretarias.php" method="post">
                <label for="NumEmp"></label>
                <input type="text" class = "caja" id="NumEmp" placeholder="Nombre de usuario" name="NumEmp" class="nombre" required>
                <br>
                <label for="Password"></label>
                <input type="password" class = "caja" id="Password" placeholder="Contraseña" name="Password" class="pass" required>
                <br>
                <button type="submit"  name="register">
                    Iniciar
                    <div class="arrow-wrapper">
                        <div class="arrow"></div>
                    </div>
                </button>
            </form>
            </div>
            
            </div>
        </div>


    
 
</body>
</html>
