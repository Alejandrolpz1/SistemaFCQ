<?php

include('funciones.php');

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
    <link rel="stylesheet" href="style_login.css">
    <title>Login de Secretarias</title>
</head>
<body>

     <!--||||||||||||||||||||BANNER|||||||||||||||||||-->
     <div class="conatiner">
       <div class="banner">
            <div class="banner-text">
                <div class="mensajePrincipal"><h2>Sistema De Control Escolar FCQ</h2></div>
                
                
                    <div class="imagen">
                        <img src="iconos/FCQ_logo.png" alt="img">
                    </div>
        </div>
        </div>
    </div>


        <div class="separador">
            <br>
            
            
        </div>

    
    
    
    <?php if (isset($mensaje_error)): ?>
        <p style="color: red;"><?php echo $mensaje_error; ?></p>
    <?php endif; ?>
 <!--|||||||||||||||FORMULARIO SECRETARIAS|||||||||||-->
 <div class="containers">
    <div class="login-containers"><!---->
        <div class="register">
            <h2>iniciar sesion</h2>
            <form action="LoginSecretarias.php" method="post">
                <label for="NumEmp"></label>
                <input type="text" id="NumEmp" placeholder="Nombre de usuario" name="NumEmp" class="nombre" required>
                <br>
                <label for="Password"></label>
                <input type="password" id="Password" placeholder="Contraseña" name="Password" class="pass" required>
                <br>
                <input type="submit" class="submit" name="register" value="Iniciar">
            </form>
            </div>
    
    <footer class="footer">
    
  </footer>

</body>
</html>
