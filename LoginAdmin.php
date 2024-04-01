<?php
include('funciones.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = isset($_POST['Usuario']) ? filter_var($_POST['Usuario'], FILTER_SANITIZE_STRING) : '';
    $password_hashed = isset($_POST['Password']) ? $_POST['Password'] : ''; // El usuario ingresa el hash de la contraseña

    if (empty($usuario) || empty($password_hashed)) {
        $mensaje_error = "Por favor, completa todos los campos.";
    } else {
        $admin_valido = consultarLoginAdmin($usuario, $password_hashed);

        if ($admin_valido) {
            session_start();
            session_regenerate_id();
            $_SESSION = array();

            $_SESSION['admin_usuario'] = $admin_valido['Usuario'];

            header("Location: IndexAdmin.php");
            exit();
        } else {
            $mensaje_error = "Usuario o contraseña incorrectos";
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
    <title>Login de Administrador</title>
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
                <h2>Iniciar sesión</h2>
                <form action="LoginAdmin.php" method="post">
                    <label for="Usuario">Nombre de usuario:</label>
                    <input type="text" id="Usuario" placeholder="Nombre de usuario" name="Usuario" class="nombre" required>
                    <br>
                    <label for="Password">Contraseña (hash):</label>
                    <input type="password" id="Password" placeholder="Contraseña (hash)" name="Password" class="pass" required>
                    <br>
                    <input type="submit" class="submit" name="register" value="Iniciar">
                </form>
            </div>
        </div>
    </div>
    <footer class="footer"></footer>
</body>
</html>
