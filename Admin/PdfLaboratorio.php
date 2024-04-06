<?php
include_once "../funciones.php"; // Incluir el archivo funciones.php
$conexion = conectarDB(); // Establecer la conexión a la base de datos
require_once('../tcpdf/tcpdf.php');

// Obtener datos del director

$sqlDirector = "SELECT Nombre, Apellido, formacion_academica FROM admin WHERE Cargo = 'Director'";
$stmtDirector = $conexion->prepare($sqlDirector);
$stmtDirector->execute();
$director = $stmtDirector->fetch(PDO::FETCH_ASSOC);

// Obtener datos del coordinador académico
$sqlCoordinador = "SELECT Nombre, Apellido, formacion_academica FROM admin WHERE Cargo = 'Coordinador Académico'";
$stmtCoordinador = $conexion->prepare($sqlCoordinador);
$stmtCoordinador->execute();
$coordinador = $stmtCoordinador->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['datos_laboratorio'])) {
    $registros = json_decode($_POST['datos_laboratorio'], true);

    // Configuración del PDF
    $pdf = new TCPDF();
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AddPage();

    $pdf->SetFont('Helvetica', '', 11);
    // Logo de la UABJO en el lado izquierdo superior
    $pdf->Image('../iconos/UBAJOLOGOCONFONDO.jpg', 10, 7, 20, '', 'JPG');
    // Logo de la FCQ en el lado derecho superior
    $pdf->Image('../iconos/FCQLOGOCONFONDO.jpg', 180, 7, 20, '', 'JPG');
    // Título de la Universidad en el centro
    $pdf->Cell(0, 10, "UNIVERSIDAD AUTÓNOMA \"BENITO JUÁREZ\" DE OAXACA", 0, 1, 'C');
    $pdf->Image('../iconos/Adorno.png', 45, 18, 120, '', 'PNG');

    // Subtítulo de la Facultad centrado y más pequeño
    $pdf->SetFont('Helvetica', 'B', 10);
    $pdf->Cell(0, 8, "FACULTAD DE CIENCIAS QUÍMICAS", 0, 1, 'C');

    $pdf->Cell(0, 7, "Lista de Laboratorios", 0, 1, 'C');

    // Agregar espacio antes de la tabla
    $pdf->Cell(0, 5, "", 0, 1);

    // Calcular la posición x para centrar la tabla horizontalmente
    $tableWidth = 40 + 60 + 60; // Suma de los anchos de las celdas
    $tableX = ($pdf->getPageWidth() - $tableWidth) / 2;

    // Tabla de laboratorios
    $pdf->SetFont('Helvetica', 'B', 10);
    $pdf->SetXY($tableX, $pdf->GetY()); // Establecer posición x
    $pdf->Cell(20, 7, "Num. Lab.", 1);
    $pdf->Cell(80, 7, "Nombre Laboratorio", 1);
    $pdf->Cell(60, 7, "Encargado", 1);
    $pdf->Ln();

    $pdf->SetFont('Helvetica', '', 8);
    // Detalles de los laboratorios
    foreach ($registros as $value) {
        $pdf->SetX($tableX); // Establecer posición x para cada fila
        $pdf->Cell(20, 5, $value["IdLaboratorios"], 1);
        $pdf->Cell(80, 5, $value["NomLaboratorio"], 1);
        $pdf->Cell(60, 5, $value["NombreProfesor"] . ' ' . $value["ApellidosProfesor"], 1);
        $pdf->Ln();
    }

    // Espacios para firmas
    $pdf->Ln(20);
    $pdf->Cell(120, 5, "        ___________________________", 0, 0);
    $pdf->Cell(80, 5, " ___________________________", 0, 1);
    $pdf->Cell(125, 5, "                Coordinador Académico ", 0, 0);
    $pdf->Cell(80, 5, "                    Director", 0, 1);
    $pdf->Cell(115, 5, "       {$coordinador['formacion_academica']} {$coordinador['Nombre']} {$coordinador['Apellido']}", 0, 0);
    $pdf->Cell(80, 5, "{$director['formacion_academica']} {$director['Nombre']} {$director['Apellido']}", 0, 1);

    // Obtener la fecha y hora actual
    date_default_timezone_set('America/Mexico_City'); // Establecer zona horaria a México
    $fecha_actual = date("d/m/Y H:i:s");

    // Agregar el mensaje al final del PDF
    $pdf->Ln(10);
    $pdf->Cell(40, 10, "Generado el $fecha_actual.", 0, 1, 'C');

    // Mostrar el PDF en el navegador
    ob_end_clean(); // Limpiar cualquier salida de buffer anterior
    $pdf->Output("Lista_Laboratorios.pdf", 'I');
    exit;
} else {
    // Redireccionar si no se han recibido los datos adecuados
    header("Location: LoginAdmin.php");
    exit();
}
?>
