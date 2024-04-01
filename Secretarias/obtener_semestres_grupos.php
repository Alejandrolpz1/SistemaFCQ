<?php
include '../funciones.php'; // Asegúrate de incluir el archivo funciones.php

if (isset($_GET['claveCarrera'])) {
    $claveCarrera = $_GET['claveCarrera'];

    try {
        $conexion = conectarDB(); // Conecta a la base de datos
        
        // Consulta para obtener los semestres únicos asociados a la carrera
        $consultaSemestres = $conexion->prepare("SELECT DISTINCT Semestre FROM alumnos WHERE formacion = :claveCarrera");
        $consultaSemestres->bindParam(':claveCarrera', $claveCarrera);
        $consultaSemestres->execute();
        $semestres = $consultaSemestres->fetchAll(PDO::FETCH_ASSOC);

        // Consulta para obtener los grupos únicos asociados a la carrera
        $consultaGrupos = $conexion->prepare("SELECT DISTINCT Grupo FROM alumnos WHERE formacion = :claveCarrera");
        $consultaGrupos->bindParam(':claveCarrera', $claveCarrera);
        $consultaGrupos->execute();
        $grupos = $consultaGrupos->fetchAll(PDO::FETCH_ASSOC);

        // Combina los resultados de semestres y grupos en un array asociativo
        $resultados = array('semestres' => $semestres, 'grupos' => $grupos);
        echo json_encode($resultados); // Devuelve los resultados en formato JSON
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Error de conexión: ' . $e->getMessage()));
    }
} else {
    echo json_encode(array('error' => 'Clave de carrera no proporcionada'));
}
?>
