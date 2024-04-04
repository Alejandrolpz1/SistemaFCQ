<?php
include('../funciones.php');

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
            $_SESSION['admin_nombre'] = $admin_valido['NombreCompleto']; // Aquí debes reemplazar 'NombreCompleto' con el nombre del campo que contiene el nombre completo en tu base de datos

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
    <link rel="stylesheet" href="../css/cssloging.css">
    <title>Login de Administrador</title>
</head>

<body>
    <nav>
        <div class="contenedor3">
            <div class="parteu">
                <h1 id="tituloLaboratorio">
                    <img src="../iconos/logoFCQ.png" width="80">  Sistema de Control Escolar FCQ 
                    <img src="../iconos/UABJOEscudo.png" width="75">
                </h1>
            </div>
        </div>
    </nav>

    <div class="contenedor3">
        <div class="parte">
            <?php if (isset($mensaje_error)): ?>
                <p style="color: red;"><?php echo $mensaje_error; ?></p>
            <?php endif; ?>
            <h2>Iniciar sesión</h2><br>
            <form action="LoginAdmin.php" method="post">
                <label for="Usuario">Nombre de usuario:</label>
                <input type="text" class="caja" id="Usuario" placeholder="Nombre de usuario" name="Usuario" class="nombre" required>
                <br>
                <label for="Password">Contraseña (hash):</label>
                <input type="password" class="caja" id="Password" placeholder="Contraseña (hash)" name="Password" class="pass" required>
                <br>
                <button type="submit" name="register">
                    Iniciar
                    <div class="arrow-wrapper">
                        <div class="arrow"></div>
                    </div>
                </button>
            </form>
        </div>
    </div>
</body>

</html>
