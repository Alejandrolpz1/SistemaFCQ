<?php
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: LoginProfesores.php");
    exit();
}

include('../funciones.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['numEmpProfesor']) && isset($_POST['claveMateria']) && isset($_POST['calificaciones'])) {
    $numEmpProfesor = $_POST['numEmpProfesor'];
    $claveMateria = $_POST['claveMateria'];
    $calificaciones = $_POST['calificaciones'];
    $matriculas = $_POST['matriculas']; // Nuevos datos: matrículas de los alumnos

    // Comprobamos si las matrices de calificaciones y matrículas tienen la misma longitud
    if (count($calificaciones) !== count($matriculas)) {
        // Si no tienen la misma longitud, hay un problema en los datos recibidos
        echo "<script>alert('Error: las calificaciones y las matrículas no tienen la misma longitud');</script>";
        echo "<script>window.history.back();</script>";
        exit();
    }

    // Convertimos las matrices asociativas de calificaciones y matrículas a una sola matriz donde las calificaciones
    // son las claves y las matrículas son los valores correspondientes
    $data = array_combine($matriculas, $calificaciones);

    if (ingresarCalificaciones($data, $claveMateria)) {
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
