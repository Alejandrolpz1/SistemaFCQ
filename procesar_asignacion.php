<?php
session_start();

include 'funciones.php';

// Verificar la sesión del administrador
if (!isset($_SESSION['admin_usuario']) || empty($_SESSION['admin_usuario'])) {
    header("Location: LoginAdmin.php");
    exit();
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el número de empleado del profesor y las materias seleccionadas
    $numEmp = $_POST['profesor'];
    $materiasSeleccionadas = isset($_POST['materias']) ? $_POST['materias'] : array();

    // Asociar las materias al profesor
    asociarMateriaProfesor($numEmp, $materiasSeleccionadas);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Asignación</title>
    <script>
        // Función para redireccionar a una página
        function redireccionar(url) {
            window.location.href = url;
        }
    </script>
</head>
<body>
    <script>
        // Mostrar mensaje emergente
        alert("Materias agregadas correctamente.");

        // Redireccionar a la página de Agregar Materias
        redireccionar("AgregarMaterias.php");
    </script>
</body>
</html>
