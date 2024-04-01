<?php
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: LoginProfesores.php");
    exit();
}

include 'funciones.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['numEmpProfesor']) && isset($_POST['claveMateria']) && isset($_POST['calificaciones'])) {
    $numEmpProfesor = $_POST['numEmpProfesor'];
    $claveMateria = $_POST['claveMateria'];
    $calificaciones = $_POST['calificaciones'];

    if (ingresarCalificaciones($calificaciones, $claveMateria)) {
        // Calificaciones agregadas exitosamente
        echo "<script>alert('Calificaciones agregadas exitosamente');</script>";
        echo "<script>window.location.href = 'IndexProfesores.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al actualizar las calificaciones');</script>";
        echo "<script>window.history.back();</script>";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
