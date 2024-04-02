<?php
include('../funciones.php');
session_start(); // Inicia la sesión al principio del script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numEmp = isset($_POST['NumEmp']) ? filter_var($_POST['NumEmp'], FILTER_SANITIZE_STRING) : '';
    $password = isset($_POST['Password']) ? filter_var($_POST['Password'], FILTER_SANITIZE_STRING) : '';

    if (empty($numEmp) || empty($password)) {
        $mensaje_error = "Por favor, completa todos los campos.";
    } else {
        $usuario_valido = consultarLoginProfesores($numEmp);

        if ($usuario_valido && password_verify($password, $usuario_valido['Password'])) {
            session_regenerate_id();
            $_SESSION = array();

            $_SESSION['user_id'] = $usuario_valido['NumEmp'];
            $_SESSION['user_name'] = $usuario_valido['Nombre'];

            header("Location: IndexProfesores.php");
            exit();
        } else {
            $mensaje_error = "Credenciales incorrectas";
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
    <title>Login de Profesores</title>
</head>
<body>
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

    <div class="containers">
        <div class="login-containers">
            <div class="register">
                <h2>iniciar sesion</h2>
                <form action="LoginProfesores.php" method="post">
                    <label for="NumEmp"></label>
                    <input type="text" id="NumEmp" placeholder="Usuario" name="NumEmp" class="nombre" required>
                    <br>
                    <label for="Password"></label>
                    <input type="password" id="Password" placeholder="Contraseña" name="Password" class="pass" required>
                    <br>
                    <input type="submit" class="submit" name="register" value="Iniciar">
                </form>
            </div>
        </div>
    </div>

    <footer class="footer"></footer>
</body>
</html>
