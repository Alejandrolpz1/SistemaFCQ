<?php
session_start();

// Elimina todas las variables de sesión
$_SESSION = array();

// Borra la cookie de sesión si está definida
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Destruye la sesión
session_destroy();

// Redirige a la página de inicio de sesión (o cualquier otra página que desees)
header("Location: LoginProfesores.php");
exit();
?>
