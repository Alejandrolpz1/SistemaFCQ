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
    <link rel="stylesheet" href="../css/cssloging.css">
</head>

<body>
    <div class="contenedor3">
        <div class="parteu">
            <h1 id="tituloLaboratorio"><img src="../iconos/logoFCQ.png" width="80">  Sistema de Control Escolar FCQ <img src="../iconos/UABJOEscudo.png" width="75"></h1>
        </div>
    </div>

    <div class="contenedor3">
        <div class="parteu">
            <?php if (isset($mensaje_error)): ?>
                <p style="color: red;"><?php echo $mensaje_error; ?></p>
            <?php endif; ?>

            <h2>Iniciar Sesión</h2><br>
            <form action="LoginProfesores.php" method="post">
                <label for="NumEmp">Nombre de Usuario</label>
                <input type="text" id="NumEmp" class="caja" placeholder="Usuario" name="NumEmp" class="nombre" required>

                <label for="Password">Contraseña:</label>
                <input type="password" class="caja" id="Password" placeholder="Contraseña" name="Password" class="pass" required>

                <button type="submit" name="register">
                    Iniciar
                    <div class="arrow-wrapper">
                        <div class="arrow"></div>
                    </div>
                </button>
            </form>
        </div>
    </div>

    <footer class="footer"></footer>
</body>

</html>
