<?php

//Conexion a la base de datos//

function conectarDB() {
    // Detalles de conexión
    $host = 'localhost';
    $usuario = 'root';
    $password = '';
    $dbname = 'mydb';

    try {
        $conexion = new PDO("mysql:host={$host};dbname={$dbname}", $usuario, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $conexion;
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
        return null;
    }
}

function consultarLoginSecretarias($numEmp, $password) {
    $conexion = conectarDB();

    try {
        // Consulta preparada para evitar la inyección SQL



        $consulta = $conexion->prepare("SELECT *FROM secretarias WHERE NumEmp = :numEmp");

        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_STR);
        $consulta->execute();

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

       
        if ($usuario && $password === $usuario['Password']) {
            return $usuario;
        } else {
            return false;
        }
    } catch (PDOException $e) {
       
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        
        $conexion = null;
    }
}

/*function SesionSecretarias() {
    session_start();
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
     header("Location: LoginSecretarias.php");
        exit();
    }
    
    $inactive_time = 1800; 
    
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive_time)) {
        // Si ha pasado demasiado tiempo, cerrar sesión
        session_unset();
        session_destroy();
        header("Location: LoginSecretarias.php");
        exit();
    }
    
    // Actualizar la marca de tiempo de la última actividad
    $_SESSION['last_activity'] = time();
}*/


function eliminarAdmin($id) {
    $conexion = conectarDB();

    try {
        // Consulta preparada para evitar la inyección SQL
        $consulta = $conexion->prepare("DELETE FROM admin WHERE Id = :id");

        $consulta->bindParam(':id', $id, PDO::PARAM_INT);

        return $consulta->execute();
    } catch (PDOException $e) {
        // Manejo de errores (puedes personalizar esto según tus necesidades)
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function agregarSecretaria($numEmp, $nombre, $apellido, $password) {
    $conexion = conectarDB();

    try {
        // Consulta preparada para evitar la inyección SQL
        $consulta = $conexion->prepare("INSERT INTO secretarias (NumEmp, Nombre, Apellido, Password) VALUES (:numEmp, :nombre, :apellido, :password)");

        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        $consulta->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindParam(':apellido', $apellido, PDO::PARAM_STR);
        $consulta->bindParam(':password', $password, PDO::PARAM_STR);

        $consulta->execute();

        return true;
    } catch (PDOException $e) {
        // Manejo de errores (puedes personalizar esto según tus necesidades)
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}


function editarSecretaria($numEmp, $nombre, $apellido, $password) {
    $conexion = conectarDB();

    try {
        // Consulta preparada para evitar la inyección SQL
        $consulta = $conexion->prepare("UPDATE secretarias SET Nombre = :nombre, Apellido = :apellido, Password = :password WHERE NumEmp = :numEmp");

        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        $consulta->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindParam(':apellido', $apellido, PDO::PARAM_STR);
        $consulta->bindParam(':password', $password, PDO::PARAM_STR);

        return $consulta->execute();
    } catch (PDOException $e) {
        // Manejo de errores (puedes personalizar esto según tus necesidades)
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function obtenerSecretariaPorNumEmp($numEmp) {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->prepare("SELECT * FROM secretarias WHERE NumEmp = :numEmp");
        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return null;
    } finally {
        $conexion = null;
    }
}

function obtenerListaSecretarias() {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->query("SELECT * FROM secretarias");
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return array();
    } finally {
        $conexion = null;
    }
}



function eliminarSecretaria($numEmp) {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->prepare("DELETE FROM secretarias WHERE NumEmp = :numEmp");
        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        return $consulta->execute();
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        $conexion = null;
    }
}



function agregarProfesor($numEmp, $nombre, $apellidos, $correo, $telefono, $passwordHash) {
    $conexion = conectarDB();

    try {
        // Consulta preparada para evitar la inyección SQL
        $consulta = $conexion->prepare("INSERT INTO profesores (NumEmp, Nombre, Apellidos, Correo, Telefono, Password) VALUES (:numEmp, :nombre, :apellidos, :correo, :telefono, :password)");

        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        $consulta->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
        $consulta->bindParam(':correo', $correo, PDO::PARAM_STR);
        $consulta->bindParam(':telefono', $telefono, PDO::PARAM_INT);
        $consulta->bindParam(':password', $passwordHash, PDO::PARAM_STR); // Usamos el hash de la contraseña

        $consulta->execute();

        return true;
    } catch (PDOException $e) {
        // Manejo de errores (puedes personalizar esto según tus necesidades)
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}


function editarProfesor($numEmp, $nombre, $apellidos, $correo, $telefono, $password) {
    $conexion = conectarDB();

    try {
        // Consulta preparada para evitar la inyección SQL
        $consulta = $conexion->prepare("UPDATE profesores SET Nombre = :nombre, Apellidos = :apellidos, Correo = :correo, Telefono = :telefono, Password = :password WHERE NumEmp = :numEmp");

        $consulta->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
        $consulta->bindParam(':correo', $correo, PDO::PARAM_STR);
        $consulta->bindParam(':telefono', $telefono, PDO::PARAM_INT);
        $consulta->bindParam(':password', $password, PDO::PARAM_STR); // La contraseña ya está cifrada
        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);

        return $consulta->execute();
    } catch (PDOException $e) {
        // Manejo de errores (puedes personalizar esto según tus necesidades)
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}


function obtenerProfesorPorNumEmp($numEmp) {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->prepare("SELECT * FROM profesores WHERE NumEmp = :numEmp");
        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return null;
    } finally {
        $conexion = null;
    }
}

function obtenerListaProfesores() {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->query("SELECT * FROM profesores");
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return array();
    } finally {
        $conexion = null;
    }
}




function consultarLoginProfesores($numEmp) {
    $conexion = conectarDB();

    try {
        // Consulta preparada para evitar la inyección SQL
        $consulta = $conexion->prepare("SELECT * FROM profesores WHERE NumEmp = :numEmp");
        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_STR);
        $consulta->execute();

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        return $usuario; // Devuelve el registro del usuario encontrado
    } catch (PDOException $e) {
        // Manejo de errores
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function consultarLoginAdmin($usuario, $password) {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->prepare("SELECT * FROM admin WHERE Usuario = :usuario");
        $consulta->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        $admin = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($admin && $password === $admin['Password']) {
            return $admin;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}
//Ocupada para ConfirmacionEliminarAlumno.php y EditarAlumnos.php
/*function consultarAlumnosWhereMatricula($matriculalumno) {
    $conexion = conectarDB();

    try {
        // Consulta SQL
        $statement = $conexion->prepare("SELECT * FROM alumnos WHERE Matricula = :matricula");
        $statement->bindParam(':matricula', $matriculalumno, PDO::PARAM_INT);
        $statement->execute();
        $registros = $statement->fetch();

        return $registros;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}*/
function consultarAlumnosWhereMatricula($matriculalumno) {
    $conexion = conectarDB();

    try {
        // Consulta SQL
        $statement = $conexion->prepare("SELECT * FROM alumnos WHERE Matricula = :matricula");
        $statement->bindParam(':matricula', $matriculalumno, PDO::PARAM_INT);
        $statement->execute();
        $registros = $statement->fetch();

        // Devolver un array vacío si no hay resultados
        return $registros ? $registros : [];
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return [];  // Devolver un array vacío en caso de error
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}
function consultarLaboratoriosWhereMatricula($idLaboratorio) {
    $conexion = conectarDB();
    try {
        // Consulta SQL
        $statement = $conexion->prepare("SELECT *FROM laboratorios WHERE IdLaboratorios = :id");
        $statement->bindParam(':id',$idLaboratorio, PDO::PARAM_STR);
        $statement->execute();
        $registros = $statement->fetch();
        // Devolver un array vacío si no hay resultados
        return $registros ? $registros : [];
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return [];  // Devolver un array vacío en caso de error
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}


function eliminarAlumnosWhereMatricula($matriculalumno) {
    $conexion = conectarDB();

    try {
        $sql = "DELETE FROM alumnos WHERE Matricula = :Matricula";
		$sql = $conexion->prepare($sql);
		$sql -> bindParam(':Matricula', $matriculalumno,PDO::PARAM_STR);
		$qryExecute = $sql->execute();
		if($qryExecute){
            header("refresh:1;url=PanelAlumnos.php");
            exit("Operación exitosa. Redirigiendo en 1 segundo...");
		}
		else{
            header("refresh:3;url=PanelAlumnos.php");
            exit("Ha ocurrido un error. Redirigiendo en tres segundos...");
        }
        return $registros;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function actualizarAlumnos($matricula, $nombre, $apellido, $telefono, $correo, $grupo1, $semestre1 ){
    $conexion = conectarDB();
   
    try {
        // Utilizar sentencias preparadas para una mejor seguridad
        $sql = "UPDATE alumnos SET Matricula = :matricula, Nombre = :nombre, Apellido = :apell, Telefono = :tel, Correo = :email, Semestre = :semestre, Grupo = :grupo WHERE Matricula = :matricula1";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':matricula', $matricula, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apell', $apellido, PDO::PARAM_STR);
        $stmt->bindParam(':tel', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':email', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':semestre', $semestre1, PDO::PARAM_STR);
        $stmt->bindParam(':grupo', $grupo1, PDO::PARAM_STR);
        $stmt->bindParam(':matricula1', $matricula, PDO::PARAM_STR);

        // Ejecutar la consulta
        $qryExecute = $stmt->execute();

        if ($qryExecute) {
            header("refresh:1;url=PanelAlumnos.php");
            exit("Datos Actualizados. Redirigiendo en 1 segundos...");

        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        $conexion = null;
    }
}

function agregarAdmin($nombre, $apellido, $cargo, $usuario, $password) {
    $conexion = conectarDB();

    try {
        // Consulta preparada para evitar la inyección SQL
        $consulta = $conexion->prepare("INSERT INTO admin (Nombre, Apellido, Cargo, Usuario, Password) VALUES (:nombre, :apellido, :cargo, :usuario, :password)");

        $consulta->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindParam(':apellido', $apellido, PDO::PARAM_STR);
        $consulta->bindParam(':cargo', $cargo, PDO::PARAM_STR);
        $consulta->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->bindParam(':password', $password, PDO::PARAM_STR);

        $consulta->execute();

        return true;
    } catch (PDOException $e) {
        // Manejo de errores (puedes personalizar esto según tus necesidades)
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}
//Obtener todos los semestres
function obtenerSemestre() {
    $conexion = conectarDB();

    try {
        $consultaSemestres = $conexion->prepare("SELECT DISTINCT Semestre FROM alumnos;");
        $consultaSemestres->execute();
        return $consultaSemestres;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}
//Obtener todos los grupos
function obtenerGrupo() {
    $conexion = conectarDB();

    try {
        $consultaGrupos = $conexion->prepare("SELECT DISTINCT Grupo FROM alumnos;");
        $consultaGrupos->execute();
        return $consultaGrupos;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}


function editarAdmin($id, $nombre, $apellido, $cargo, $usuario, $password) {
    $conexion = conectarDB();

    try {
        // Consulta preparada para evitar la inyección SQL
        $consulta = $conexion->prepare("UPDATE admin SET Nombre = :nombre, Apellido = :apellido, Cargo = :cargo, Usuario = :usuario, Password = :password WHERE Id = :id");

        $consulta->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindParam(':apellido', $apellido, PDO::PARAM_STR);
        $consulta->bindParam(':cargo', $cargo, PDO::PARAM_STR);
        $consulta->bindParam(':usuario', $usuario, PDO::PARAM_STR);

        // Hash de la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $consulta->bindParam(':password', $passwordHash, PDO::PARAM_STR);

        $consulta->bindParam(':id', $id, PDO::PARAM_INT);

        return $consulta->execute();
    } catch (PDOException $e) {
        // Manejo de errores (puedes personalizar esto según tus necesidades)
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function obtenerListaAdmins() {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->query("SELECT * FROM admin");
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return array();
    } finally {
        $conexion = null;
    }
}

function obtenerAdminPorId($id) {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->prepare("SELECT * FROM admin WHERE Id = :id");
        $consulta->bindParam(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return null;
    } finally {
        $conexion = null;
    }
}



function eliminarProfesor($numEmp) {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->prepare("DELETE FROM profesores WHERE NumEmp = :numEmp");
        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);

        return $consulta->execute();
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        $conexion = null;
    }
}




function obtenerNombreProfesor($numEmp) {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->prepare("SELECT Nombre  FROM profesores WHERE NumEmp = :numEmp");
        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        $consulta->execute();

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            return $resultado['Nombre'];
        } else {
            return "Nombre no encontrado";
        }
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        $conexion = null;
    }
}
//Generar Kardex del alumno
function generarPDFKardex($numero_matricula, $ciclo_escolar) {
    // Obtener información del alumno y sus calificaciones
    $conexion = conectarDB();
    $sql = "SELECT a.Matricula, a.Nombre, a.Apellido, a.Semestre, a.Grupo, m.Clave, m.Nombre as Materia, c.Calificacion, f.Nombre as Carrera 
                FROM alumnos a 
                INNER JOIN cursar c ON a.Matricula = c.Matricula_Alumno 
                INNER JOIN materias m ON c.Clave_Materia = m.Clave 
                INNER JOIN formacion f ON m.ClaveFormacion = f.Clave 
                WHERE a.Matricula = :matricula AND c.Ciclo_Escolar = :ciclo_escolar";

        $statement = $conexion->prepare($sql);
        $statement->bindParam(':matricula', $numero_matricula, PDO::PARAM_INT);
        $statement->bindParam(':ciclo_escolar', $ciclo_escolar, PDO::PARAM_STR);
        $statement->execute();
        $resultado = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (!$resultado) {
        echo "<p>No se encontraron calificaciones para el alumno.</p>";
        return;
    }
    // Obtener datos del director
    $sqlDirector = "SELECT Nombre,Apellido, formacion_academica FROM admin WHERE Cargo = 'Director'";
    $stmtDirector = $conexion->prepare($sqlDirector);
    $stmtDirector->execute();
    $director = $stmtDirector->fetch(PDO::FETCH_ASSOC);

    // Obtener datos del coordinador académico
    $sqlCoordinador = "SELECT Nombre,Apellido, formacion_academica FROM admin WHERE Cargo = 'Coordinador Académico'";
    $stmtCoordinador = $conexion->prepare($sqlCoordinador);
    $stmtCoordinador->execute();
    $coordinador = $stmtCoordinador->fetch(PDO::FETCH_ASSOC);

    $pdf = new TCPDF();
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AddPage();
    // Agregar contenido al PDF (puedes personalizar esto según tus necesidades)
    $pdf->SetFont('Helvetica', '', 11);
    // Logo de la UABJO en el lado izquierdo superior
    $pdf->Image('iconos/UBAJOLOGOCONFONDO.jpg', 10, 7, 20, '', 'JPG');
    // Logo de la FCQ en el lado derecho superior
    $pdf->Image('iconos/FCQLOGOCONFONDO.jpg', 180, 7, 20, '', 'JPG');
    // Título de la Universidad en el centro
    $pdf->Cell(0, 10, "UNIVERSIDAD AUTÓNOMA \"BENITO JUÁREZ\" DE OAXACA", 0, 1, 'C');
    $pdf->Image('iconos/Adorno.png', 45, 18, 120, '', 'PNG');

    // Subtítulo de la Facultad centrado y más pequeño
    $pdf->SetFont('Helvetica', 'B', 10);
    
    $pdf->Cell(0, 8, "FACULTAD DE CIENCIAS QUÍMICAS", 0, 1, 'C');

    $pdf->Cell(0, 7, "Kardex de Calificaciones", 0, 1, 'C');

    // Agregar espacio antes de la foto
    $pdf->Cell(0, 5, "", 0, 1);

// Definir el tamaño del cuadro para la foto infantil
    $tamano_cuadro_ancho = 25;
    $tamano_cuadro_alto = 30;

    $posicion_x = 10;
    $posicion_y = $pdf->GetY();

    // Dibujar el rectángulo para la foto infantil
    $pdf->Rect($posicion_x, $posicion_y, $tamano_cuadro_ancho, $tamano_cuadro_alto, 'D');

    // Calcular la posición Y para la información (más arriba, pero sin superponerse con el cuadro)
    $posicion_y_info = max($posicion_y, $pdf->GetY() - 10);

    $pdf->SetY($posicion_y_info);

    // Configurar la posición X para los datos del alumno
    $posicion_x_datos_alumno = $posicion_x + $tamano_cuadro_ancho + 10;

    $pdf->SetX($posicion_x_datos_alumno);

    $pdf->SetFont('Helvetica', '', 10);
    $fila_alumno = $resultado[0];

    $pdf->Cell(0, 5, "", 0, 10);

    // Agregar datos del alumno al lado del rectángulo
    $pdf->Cell(20, 5, "Matrícula:", 0, 0, 'B');
    $pdf->Cell(42, 5, $fila_alumno['Matricula'], 0, 0);
    $pdf->Cell(23, 5, "Nombre:", 0, 0, 'B');
    $pdf->Cell(60, 5, $fila_alumno['Nombre'] . ' ' . $fila_alumno['Apellido'], 0, 1);

    // Configurar la posición X para las siguientes celdas
    $pdf->SetX($posicion_x_datos_alumno);

    $pdf->Cell(20, 5, "Carrera:", 0, 0, 'B');
    $pdf->Cell(42, 5, $fila_alumno['Carrera'], 0, 0);
    $pdf->Cell(23, 5, "Ciclo Escolar:", 0, 0, 'B');
    $pdf->Cell(60, 5, $ciclo_escolar, 0, 1);

    // Configurar la posición X para las siguientes celdas
    $pdf->SetX($posicion_x_datos_alumno);

    $pdf->Cell(20, 5, "Semestre:", 0, 0, 'B');
    $pdf->Cell(42, 5, $fila_alumno['Semestre'], 0, 0);
    $pdf->Cell(23, 5, "Grupo:", 0, 0, 'B');
    $pdf->Cell(60, 5, $fila_alumno['Grupo'], 0, 1);

    $pdf->Cell(0, 7, "", 0, 1); // Agregar espacio

    // Tabla de calificaciones
    $pdf->Cell(0, 10, "", 0, 1); // Agregar espacio
    $pdf->SetFont('Helvetica', 'B', 10);
    $pdf->Cell(60, 7, "Clave de Materia", 1);
    $pdf->Cell(60, 7, "Nombre de Materia", 1);//1 ES PARA MARGEN DE LAS TABLAS
    $pdf->Cell(60, 7, "Calificación", 1);
    $pdf->Ln();

    $pdf->SetFont('Helvetica', 8);
    // Detalles de las calificaciones
    foreach ($resultado as $fila_materia) {
        $pdf->Cell(60, 5, $fila_materia['Clave'], 1);
        $pdf->Cell(60, 5, $fila_materia['Materia'], 1);
        $pdf->Cell(60, 5, $fila_materia['Calificacion'], 1);
        $pdf->Ln();
    }
    // Espacios para firmas
    $pdf->Ln(10);
    $pdf->Cell(120, 5, "        ___________________________", 0, 0);
    $pdf->Cell(80, 5, " ___________________________", 0, 1);
    $pdf->Cell(125, 5, "                Coordinador Académico ", 0, 0);
    $pdf->Cell(80, 5, "                    Director", 0, 1);
    $pdf->Cell(115, 5, "       {$coordinador['formacion_academica']} {$coordinador['Nombre']} {$coordinador['Apellido']}", 0, 0);
    $pdf->Cell(80, 5, "{$director['formacion_academica']} {$director['Nombre']} {$director['Apellido']}", 0, 1);
    date_default_timezone_set('America/Mexico_City'); // Establecer zona horaria a México
    $fecha_actual = date("d/m/Y H:i:s");

    // Agregar el mensaje al final del PDF
    $pdf->Ln(5);
    $pdf->Cell(40, 10, "Generado el $fecha_actual.", 0, 1, 'C');
    // Mostrar el PDF en el navegador
    ob_end_clean(); // Limpiar cualquier salida de buffer anterior
    $pdf->Output("Kardex_Alumno_$numero_matricula.pdf", 'I');
    exit;
}


function consultarLaboratorios() {
    $conexion = conectarDB();

    try {
        // Consulta SQL
        $query = "SELECT DISTINCT L.*, P.Nombre AS NombreProfesor, P.Apellidos AS ApellidosProfesor 
        FROM profesores P 
        INNER JOIN laboratorios L ON P.NumEmp = L.JefeNumEmp;
        ";
        // Preparar la declaración
        $statement = $conexion->prepare($query);
        // Ejecutar la consulta
        $statement->execute();
        // Obtener los resultados
        $registros = $statement->fetchAll();

        return $registros;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}
function consultarLaboratorios1() {
    $conexion = conectarDB();

    try {
        // Consulta SQL
        $query = "SELECT * FROM laboratorios;";
        // Preparar la declaración
        $statement = $conexion->prepare($query);
        // Ejecutar la consulta
        $statement->execute();
        // Obtener los resultados
        $registros1 = $statement->fetchAll();

        return $registros1;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function obtenerMateriasPorNumEmp($numEmp) {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->prepare("SELECT
            p.NumEmp AS NumEmpProfesor,
            p.Nombre AS NombreProfesor,
            l.NomLaboratorio AS Laboratorio,
            m.Clave AS ClaveMateria,
            m.Nombre AS NombreMateria
        FROM
            profesores p
        JOIN
            prof_lab pl ON p.NumEmp = pl.Num_Emp
        JOIN
            laboratorios l ON pl.IdLaboratorio = l.IdLaboratorios
        JOIN
            profesores_materias pm ON p.NumEmp = pm.NumEmp
        JOIN
            materias m ON pm.ClaveMateria = m.Clave
        WHERE
            p.NumEmp = :numEmp");
        
        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        $conexion = null;
    }
}

function obtenerMateriasYLaboratoriosPorNumEmp($numEmp) {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->prepare("SELECT
            p.NumEmp AS NumEmpProfesor,
            p.Nombre AS NombreProfesor,
            l.NomLaboratorio AS Laboratorio,
            m.Clave AS ClaveMateria,
            m.Nombre AS NombreMateria
        FROM
            profesores p
        JOIN
            prof_lab pl ON p.NumEmp = pl.Num_Emp
        JOIN
            laboratorios l ON pl.IdLaboratorio = l.IdLaboratorios
        JOIN
            profesores_materias pm ON p.NumEmp = pm.NumEmp
        JOIN
            materias m ON pm.ClaveMateria = m.Clave
        WHERE
            p.NumEmp = :numEmp");
        
        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        $conexion = null;
    }
}

function obtenerDetallesMateria($numEmpProfesor, $nombreMateria) {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->prepare("SELECT
            m.Clave AS ClaveMateria,
            m.Nombre AS NombreMateria,
            a.Matricula AS MatriculaAlumno,
            a.Nombre AS NombreAlumno,
            a.Apellido AS ApellidoAlumno,
            c.Calificacion,
            c.Ciclo_Escolar
        FROM
            profesores p
        JOIN
            profesores_materias pm ON p.NumEmp = pm.NumEmp
        JOIN
            materias m ON pm.ClaveMateria = m.Clave
        JOIN
            cursar c ON m.Clave = c.Clave_Materia
        JOIN
            alumnos a ON c.Matricula_Alumno = a.Matricula
        WHERE
            p.NumEmp = :numEmpProfesor
        AND
            m.Nombre = :nombreMateria");

        $consulta->bindParam(':numEmpProfesor', $numEmpProfesor, PDO::PARAM_INT);
        $consulta->bindParam(':nombreMateria', $nombreMateria, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        $conexion = null;
    }
}


function insertarAlumno($matricula, $nombre, $apellidos, $sexo, $telefono, $correo, $semestre, $grupo, $carrera) {
    $conexion = conectarDB();

    if ($conexion) {
        try {
            $status = "Activo";
            // Consulta de inserción
            $sql = "INSERT INTO alumnos (Matricula, Nombre, Apellido, Sexo, Telefono, Correo, Semestre, Grupo, Estatus, formacion)
                    VALUES (:matricula, :nombre, :apellido, :sexo, :telefono, :correo, :semestre, :grupo, :estatus, :formacion)";

            $consulta = $conexion->prepare($sql);
            $consulta->bindParam(':matricula', $matricula, PDO::PARAM_INT);
            $consulta->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->bindParam(':apellido', $apellidos, PDO::PARAM_STR);
            $consulta->bindParam(':sexo', $sexo, PDO::PARAM_STR);
            $consulta->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $consulta->bindParam(':correo', $correo, PDO::PARAM_STR);
            $consulta->bindParam(':semestre', $semestre, PDO::PARAM_INT);
            $consulta->bindParam(':grupo', $grupo, PDO::PARAM_STR);
            $consulta->bindParam(':estatus', $status, PDO::PARAM_STR);
            $consulta->bindParam(':formacion', $carrera, PDO::PARAM_STR);

            $consulta->execute();

            $filas = $consulta->rowCount();

            if ($filas > 0) {
                return "Alumno Agregado";
            } else {
                return "Hubo un Error al Agregar Alumno!!!";
            }
        } catch (PDOException $e) {
            return "<br>No se pudo completar el ingreso. La matrícula ya está asignada a otro estudiante.<br> Error de base de datos: " . $e->getMessage();
        } finally {
            // Cerrar la conexión
            $conexion = null;
        }
    } else {
        return "Error al conectar a la base de datos";
    }
}
function consultarAlumnos($carrera, $semestre, $grupo) {

    $conexion = conectarDB();
    try {
        $estatus = "Activo";
        // Consulta SQL
        $sql = "SELECT a.*, f.Clave 
        FROM alumnos a 
        INNER JOIN formacion f ON a.formacion = f.Clave
        WHERE f.Clave = :carrera AND a.Semestre = :semestre AND a.Grupo = :grupo AND a.Estatus = :estatus";

        // Preparar la declaración
        $statement = $conexion->prepare($sql);
        // Asociar valores a los marcadores de posición
        $statement->bindParam(':carrera', $carrera, PDO::PARAM_STR);
        $statement->bindParam(':semestre', $semestre, PDO::PARAM_STR);
        $statement->bindParam(':grupo', $grupo, PDO::PARAM_STR);
        $statement->bindParam(':estatus', $estatus, PDO::PARAM_STR);
        // Ejecutar la consulta
        $statement->execute();
        // Obtener los resultados
        $registros = $statement->fetchAll();
        

        return $registros;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function obtenerCiclosEscolares() {
    $conexion = conectarDB();

    try {
        $consultaCiclos = $conexion->prepare("SELECT DISTINCT Ciclo_Escolar FROM cursar");
        $consultaCiclos->execute();
        return $consultaCiclos;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}
function mostrarInformacionAlumno($numero_matricula, $ciclo_escolar) {
    $conexion = conectarDB();

    try {
        // Consulta SQL con las variables
        $sql = "SELECT a.Matricula, a.Nombre, a.Apellido, a.Semestre, a.Grupo, m.Clave, m.Nombre as Materia, c.Calificacion, f.Nombre as Carrera 
                FROM alumnos a 
                INNER JOIN cursar c ON a.Matricula = c.Matricula_Alumno 
                INNER JOIN materias m ON c.Clave_Materia = m.Clave 
                INNER JOIN formacion f ON m.ClaveFormacion = f.Clave 
                WHERE a.Matricula = :matricula AND c.Ciclo_Escolar = :ciclo_escolar";

        $statement = $conexion->prepare($sql);
        $statement->bindParam(':matricula', $numero_matricula, PDO::PARAM_INT);
        $statement->bindParam(':ciclo_escolar', $ciclo_escolar, PDO::PARAM_STR);
        $statement->execute();
        $resultado = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($resultado) {
            // Obtener información del alumno
            $fila_alumno = $resultado[0];

            // Mostrar tabla 1 con información del alumno
            echo "<h2>Información del Alumno</h2>";
            echo "<table>";
            echo "<tr><th>Matrícula</th><td>{$fila_alumno['Matricula']}</td></tr>";
            echo "<tr><th>Nombre</th><td>{$fila_alumno['Nombre']} {$fila_alumno['Apellido']}</td></tr>";
            echo "<tr><th>Carrera</th><td>{$fila_alumno['Carrera']}</td></tr>";
            echo "<tr><th>Ciclo Escolar</th><td>{$ciclo_escolar}</td></tr>";
            echo "<tr><th>Semestre</th><td>{$fila_alumno['Semestre']}</td></tr>";
            echo "<tr><th>Grupo</th><td>{$fila_alumno['Grupo']}</td></tr>";
            echo "</table>";

            // Mostrar tabla 2 con información de materias y calificaciones
            echo "<h2>Calificaciones</h2>";
            echo "<table>";
            echo "<tr><th>Clave de Materia</th><th>Nombre de Materia</th><th>Calificación</th></tr>";

            // Mostrar información de cada materia
            foreach ($resultado as $fila_materia) {
                echo "<tr><td>{$fila_materia['Clave']}</td><td>{$fila_materia['Materia']}</td><td>{$fila_materia['Calificacion']}</td></tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Sin coincidencias.</p>";
        }
    } catch (PDOException $e) {
        // Manejar la excepción aquí, si es necesario
        echo "Error: " . $e->getMessage();
    } finally {
        // Cerrar la conexión a la base de datos
        $conexion = null;
    }
}




//AQUI EMPIEZA OTRO

//Lista para seleccionarlas carreras disponibles asi como su gardo y grupo
function obtenerCarrerasygrupos() {
    $conexion = conectarDB();

    try {
        // Consulta SQL
        $sql = "SELECT Clave, Nombre FROM formacion";

        // Preparar la declaración
        $statement = $conexion->query($sql);

        // Obtener los resultados
        $carreras = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $carreras;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}
//lISTA DE MATERIAS POR CICLO GRADO Y GRUPO SE OCUPA EN ListaMaterias.php para vesion 1, y version 2 para ListaMateriasCicloEditarCalificacion.php
//Version 1 esta no es necesaria ya que esta la version 2 activa pero lo dejo comentado por si sirve despues
/*function mostrarMateriasPorCicloyGrados($semestre,$grupo,$cicloEscolar) {
    $conexion = conectarDB();

    try {
        // Consulta para obtener las materias del ciclo escolar
        $sql = "SELECT DISTINCT M.Clave, M.Nombre 
        FROM CURSAR C 
        INNER JOIN MATERIAS M ON C.Clave_Materia = M.Clave 
        INNER JOIN ALUMNOS A ON C.Matricula_Alumno = A.Matricula 
        WHERE A.Semestre = :semestre AND A.Grupo = :grupo AND C.Ciclo_Escolar = :ciclo";

        $statement = $conexion->prepare($sql);
        $statement->bindParam(':semestre', $semestre, PDO::PARAM_STR);
        $statement->bindParam(':grupo', $grupo, PDO::PARAM_STR);
        $statement->bindParam(':ciclo', $cicloEscolar, PDO::PARAM_STR);
        $statement->execute();
        $consultaMaterias = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Imprimir el título y la tabla de materias
        echo "<h2>Materias del Ciclo: $cicloEscolar del $semestre $grupo </h2>";
        return $consultaMaterias;
    } catch (PDOException $e) {
        // Manejar la excepción aquí, si es necesario
        echo "Error: " . $e->getMessage();
    } finally {
        // Cerrar la conexión a la base de datos
        $conexion = null;
    }
}*/
//vercion 2
function mostrarMateriasPorCicloyGradosCarrera($semestre, $grupo, $cicloEscolar, $carrera) {
    $conexion = conectarDB();

    try {
        // Consulta para obtener las materias del ciclo escolar
        $sql = "SELECT DISTINCT M.Clave, M.Nombre 
        FROM CURSAR C 
        INNER JOIN MATERIAS M ON C.Clave_Materia = M.Clave 
        INNER JOIN ALUMNOS A ON C.Matricula_Alumno = A.Matricula 
        WHERE A.Semestre = :semestre AND A.Grupo = :grupo AND C.Ciclo_Escolar = :ciclo AND M.ClaveFormacion = :clave ";

        $statement = $conexion->prepare($sql);
        $statement->bindParam(':semestre', $semestre, PDO::PARAM_STR);
        $statement->bindParam(':grupo', $grupo, PDO::PARAM_STR);
        $statement->bindParam(':ciclo', $cicloEscolar, PDO::PARAM_STR);
        $statement->bindParam(':clave',  $carrera, PDO::PARAM_STR);
        $statement->execute();
        $consultaMaterias = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $consultaMaterias;
    } catch (PDOException $e) {
        // Manejar la excepción aquí, si es necesario
        echo "Error: " . $e->getMessage();
    } finally {
        // Cerrar la conexión a la base de datos
        $conexion = null;
    }
}
//Genera las calificaciones por materia, funcion ocupada en PdfKardexPorMateria.php
function generarPDFCalificacionesMateria($clave_materia, $ciclo_escolar, $semestre, $grupo, $nombre_materia) {
    // Obtener información del alumno y sus calificaciones
    $conexion = conectarDB();
    $sql = "SELECT A.Matricula, A.Nombre, A.Apellido, C.Calificacion 
            FROM CURSAR C 
            INNER JOIN ALUMNOS A ON C.Matricula_Alumno = A.Matricula 
            INNER JOIN MATERIAS M ON C.Clave_Materia = M.Clave 
            WHERE A.Semestre = :semestre AND A.Grupo = :grupo 
                AND M.Clave = :clave_materia AND C.Ciclo_Escolar = :ciclo_escolar
            ORDER BY A.Apellido ASC;";

    //Obtener nombre profesor
    echo "$nombre_profesor";
    $nombre_profesor = ObtenerProfesorMateria($clave_materia);

    $statement = $conexion->prepare($sql);
    $statement->bindParam(':semestre', $semestre, PDO::PARAM_STR);
    $statement->bindParam(':grupo', $grupo, PDO::PARAM_STR);
    $statement->bindParam(':clave_materia', $clave_materia, PDO::PARAM_STR);
    $statement->bindParam(':ciclo_escolar', $ciclo_escolar, PDO::PARAM_STR);
    $statement->execute();
    $resultado = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (!$resultado) {
        echo "<p>No se encontraron calificaciones para la materia seleccionada.</p>";
        return;
    }

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

    $pdf->Cell(0, 7, "$nombre_materia, Calificaciones $semestre $grupo Ciclo Escolar: $ciclo_escolar", 0, 1, 'C');

    // Agregar espacio antes de la tabla
    $pdf->Cell(0, 5, "", 0, 1);

    // Calcular la posición x para centrar la tabla horizontalmente
    $tableWidth = 40 + 60 + 40; // Suma de los anchos de las celdas
    $tableX = ($pdf->getPageWidth() - $tableWidth) / 2;

    // Tabla de calificaciones
    $pdf->SetFont('Helvetica', 'B', 10);
    $pdf->SetXY($tableX, $pdf->GetY()); // Establecer posición x
    $pdf->Cell(40, 7, "Matrícula", 1);
    $pdf->Cell(60, 7, "Nombre", 1);
    $pdf->Cell(40, 7, "Calificación", 1);
    $pdf->Ln();

    $pdf->SetFont('Helvetica', '', 8);
    // Detalles de las calificaciones
    foreach ($resultado as $fila) {
        $pdf->SetX($tableX); // Establecer posición x para cada fila
        $pdf->Cell(40, 5, $fila['Matricula'], 1);
        $pdf->Cell(60, 5, $fila['Apellido']. ' ' . $fila['Nombre'] , 1);
        $pdf->Cell(40, 5, $fila['Calificacion'], 1);
        $pdf->Ln();
    }
    
    // Espacios para firmas
    $pdf->Ln(20);
    $pdf->Cell(75, 5, " ", 0, 0);
    $pdf->Cell(80, 5, " ___________________________", 0, 1);
    $pdf->Cell(75, 5, " ", 0, 0);
    $pdf->Cell(80, 5, $nombre_profesor, 0, 1); // Mostrar el nombre del profesor



    // Obtener la fecha y hora actual
    date_default_timezone_set('America/Mexico_City'); // Establecer zona horaria a México
    $fecha_actual = date("d/m/Y H:i:s");

    // Agregar el mensaje al final del PDF
    $pdf->Ln(5);
    $pdf->Cell(40, 10, "Generado el $fecha_actual.", 0, 1, 'C');

    // Mostrar el PDF en el navegador
    ob_end_clean(); // Limpiar cualquier salida de buffer anterior
    $pdf->Output("Calificaciones_Materia_$clave_materia.pdf", 'I');
    exit;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
//Se usa en ListaCalificacionesEditar.php
function AlumnosyCalificaciones($semestre, $grupo, $claveMateria, $cicloEscolar,$carrera) {
    $conexion = conectarDB();

    try {
        // Consulta para obtener informacion del alumno y sus calificaciones 
        $sql = "SELECT A.Matricula, A.Nombre as Nombre, A.Apellido, C.Calificacion, C.Clave_Materia, M.Nombre AS Materia, C.Ciclo_Escolar
                        FROM CURSAR C 
                        INNER JOIN ALUMNOS A ON C.Matricula_Alumno = A.Matricula 
                        INNER JOIN MATERIAS M ON C.Clave_Materia = M.Clave 
                        WHERE A.Semestre = :semestre AND A.Grupo = :grupo 
                            AND M.Clave = :clave_materia AND C.Ciclo_Escolar = :ciclo_escolar AND M.ClaveFormacion = :clave
                        ORDER BY A.Apellido ASC;";

                $statement = $conexion->prepare($sql);
                $statement->bindParam(':semestre', $semestre, PDO::PARAM_STR);
                $statement->bindParam(':grupo', $grupo, PDO::PARAM_STR);
                $statement->bindParam(':clave_materia', $claveMateria, PDO::PARAM_STR);
                $statement->bindParam(':ciclo_escolar', $cicloEscolar, PDO::PARAM_STR);
                $statement->bindParam(':clave', $carrera, PDO::PARAM_STR);
                $statement->execute();

                if ($statement->rowCount() > 0) {
                    // La consulta ha devuelto resultados
                    $resultados = $statement->fetchAll(PDO::FETCH_ASSOC);
                    return $resultados;
                } else {
                    // La consulta no devolvió ningún resultado
                    echo "No se encontraron resultados.";
                }
    } catch (PDOException $e) {
        // Manejar la excepción aquí, si es necesario
        echo "Error: " . $e->getMessage();
    } finally {
        // Cerrar la conexión a la base de datos
        $conexion = null;
    }
}

//se usa en EditarCalificacion.php
function AlumnosyCalificaciones2($matriculalumno) {
    $conexion = conectarDB();

    try {
        // Consulta para obtener informacion del alumno y sus calificaciones 
        $statement = $conexion->prepare("SELECT c.Matricula_Alumno,c.Clave_Materia,c.calificacion,a.Semestre,a.Grupo 
        FROM alumnos a INNER JOIN cursar c ON a.Matricula = c.Matricula_Alumno WHERE c.Matricula_Alumno = :matricula;");
        $statement->bindParam(':matricula', $matriculalumno, PDO::PARAM_STR);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $registros = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $registros;
        } else {
            // La consulta no devolvió ningún resultado
            echo "No se encontraron resultados.";
        }
    } catch (PDOException $e) {
        // Manejar la excepción aquí, si es necesario
        echo "Error: " . $e->getMessage();
    } finally {
        // Cerrar la conexión a la base de datos
        $conexion = null;
    }
}


function ActualizarCalificaciones($calificacion,$claveMateria,$matricula){
    $conexion = conectarDB();

    try {
        $sql = "UPDATE cursar SET Calificacion = :calificacion WHERE Clave_Materia = :clave AND Matricula_Alumno = :matricula";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':calificacion', $calificacion, PDO::PARAM_STR);
        $stmt->bindParam(':clave', $claveMateria, PDO::PARAM_STR);
        $stmt->bindParam(':matricula', $matricula, PDO::PARAM_STR);

        // Ejecutar la consulta
        $qryExecute = $stmt->execute();

        if ($qryExecute) {
            header("refresh:1;url=PanelAlumnos.php");
            exit("Datos Actualizados. Redirigiendo en 1 segundos...");
        } else {
            $errorInfo = $stmt->errorInfo();
            // Registrar o mostrar detalles del error
            error_log("Fail: " . $errorInfo[2], 0);
            echo "Error al ejecutar la consulta de actualización: " . $errorInfo[2];
            exit();
        }
    } catch (PDOException $e) {
        // Manejar la excepción aquí, si es necesario
        echo "Error: " . $e->getMessage();
    } finally {
        // Cerrar la conexión a la base de datos
        $conexion = null;
    }
}






///////////////////////////////////////////////////////////////////////////////////////////////////////

//Funcion Utilizada en ActualizarLaboratorios.php
function actualizarLaboratorios($idlaboratorio, $nombreLaboratorio, $encargado){
    $conexion = conectarDB();
   
    try {
        $sql = "UPDATE laboratorios SET IdLaboratorios = :id, NomLaboratorio = :nombre, JefeNumEmp = :Emp WHERE IdLaboratorios = :id1";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $idlaboratorio, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $nombreLaboratorio, PDO::PARAM_STR);
        $stmt->bindParam(':Emp', $encargado, PDO::PARAM_STR);
        $stmt->bindParam(':id1', $idlaboratorio, PDO::PARAM_STR);

        // Ejecutar la consulta
        $qryExecute = $stmt->execute();

        if ($qryExecute) {
            header("refresh:1;url=ListaLaboratorios.php");
            exit("Datos Actualizados. Redirigiendo en 1 segundos...");
        } else {
            $errorInfo = $stmt->errorInfo();
             // Registrar o mostrar detalles del error
            error_log("Fail: " . $errorInfo[2], 0);
            echo "Error al ejecutar la consulta de actualización: " . $errorInfo[2];
            exit();
        }
    } catch (PDOException $e) {
        // Manejar excepciones
        echo "Error: " . $e->getMessage();
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}




///LO PLATICO CON ALEJANDRO
function insertarLaboratorios($idLab, $nombreLab, $jefeLab) {
    $conexion = conectarDB();

    if ($conexion) {
        try {
            // Consulta de inserción
            
            //$clavemateria = consultaridmateria($nombreMateria)
            $sql = "INSERT INTO laboratorios (IdLaboratorios, NomLaboratorio, JefeNumEmp) VALUES (:id,:nombre,:jefe)";

            $consulta = $conexion->prepare($sql);
            $consulta->bindParam(':id', $idLab, PDO::PARAM_INT);
            $consulta->bindParam(':nombre', $nombreLab, PDO::PARAM_STR);
            $consulta->bindParam(':jefe', $jefeLab, PDO::PARAM_STR);
            $consulta->execute();
            $filas = $consulta->rowCount();

            if ($filas > 0) {
                return "Laboratorio Agregado";
            } else {
                return "Hubo un Error al Agregar Laboratorio!!!";
            }
        } catch (PDOException $e) {
            return "Error de base de datos: " . $e->getMessage();
        } finally {
            // Cerrar la conexión
            $conexion = null;
        }
    } else {
        return "Error al conectar a la base de datos";
    }
}

function consultarmaterias() {
    $conexion = conectarDB();

    try {
        $listamaterias = $conexion->prepare("SELECT Nombre FROM materias;");
        $listamaterias->execute();
        return $listamaterias;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function consultaridmateria($ClaveMateria) {
    $conexion = conectarDB();

    try {
        $listamaterias = $conexion->prepare("SELECT Clave FROM materias WHERE Nombre = :nombre;");
        $listamaterias->bindParam(':nombre', $ClaveMateria, PDO::PARAM_STR);
        $listamaterias->execute();
        return $listamaterias;
        echo $listamaterias;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function eliminarLaboratorio($idLaboratorio){
    $conexion = conectarDB();

    try {
        $sql = "DELETE FROM laboratorios WHERE IdLaboratorios = :IdLab";
		$sql = $conexion->prepare($sql);
		$sql -> bindParam(':IdLab', $idLaboratorio,PDO::PARAM_STR);
		$qryExecute = $sql->execute();
		if($qryExecute){
            header("refresh:1;url=ListaLaboratorios.php");
            exit("Operación exitosa. Redirigiendo en 1 segundo...");
		}
		else{
            header("refresh:3;url=ListaLaboratorios.php");
            exit("Ha ocurrido un error. Redirigiendo en tres segundo...");
        }
        return $registros;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}


function ObtenerCicloActivo() {
    $conexion = conectarDB();

    try {
        // Consulta SQL
        $statement = $conexion->prepare("SELECT * FROM ciclo_activo");
        $statement->execute();
        $registros = $statement->fetch();

        // Devolver un array vacío si no hay resultados
        return $registros ? $registros : [];
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return [];  // Devolver un array vacío en caso de error
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function ObtenerMateriasFaltantes($carrera,$semestre) {
    $conexion = conectarDB();

    try {
        $listamaterias = $conexion->prepare("SELECT * FROM materias WHERE Semestre < :semestre AND ClaveFormacion = :formacion;");
        $listamaterias->bindParam(':formacion', $carrera, PDO::PARAM_STR);
        $listamaterias->bindParam(':semestre', $semestre, PDO::PARAM_STR);
        $listamaterias->execute();
        return $listamaterias;
        echo $listamaterias;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function MateriasRecomendadas($carrera,$semestre) {
    $conexion = conectarDB();

    try {
        $listamaterias = $conexion->prepare("SELECT * FROM materias WHERE Semestre = :semestre AND ClaveFormacion = :formacion;");
        $listamaterias->bindParam(':formacion', $carrera, PDO::PARAM_STR);
        $listamaterias->bindParam(':semestre', $semestre, PDO::PARAM_STR);
        $listamaterias->execute();
        $resultados = $listamaterias->fetchAll(PDO::FETCH_ASSOC); // Convertir el resultado en un array asociativo
        return $resultados;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}


//Funcion para obtener la clave de materia, esto lo ocupa la funcion insertarAsignacionMaterias de este mismo archivo (funciones.php) para pasar las materias de su nombre a su matricula,y asi poder insertar en la tabla de alum-mat 
function obtenerClaveMateria($nombreMateria) {
    $conexion = conectarDB();

    try {
        $stmt = $conexion->prepare("SELECT Clave FROM materias WHERE Nombre = :nombre");
        $stmt->bindParam(':nombre', $nombreMateria);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            return $resultado['Clave'];
        } else {
            return null; // La materia no fue encontrada
        }
    } catch (PDOException $e) {
        echo "Error al obtener la clave de la materia: " . $e->getMessage();
        return null;
    }
}
//Funcion ocupada para asignar materias a alos alumnos ocupada aen AsignarMaterias.php
function insertarAsignacionMaterias($matriculalumno,$carrera,$semestre,$grupo, ...$materias) {
    $conexion = conectarDB();
    $totalMaterias = 0;
    $materiasInsertadas = 0;

    // Recorrer las materias seleccionadas y obtener su clave
    foreach ($materias as $materia) {
        if (!empty($materia)) {
            $totalMaterias++;
            $claveMateria = obtenerClaveMateria($materia);

            if ($claveMateria) {
                try {
                    // Insertar la asignación de materia en la tabla
                    $stmt = $conexion->prepare("INSERT INTO alum_mat (matricula_alumno, clave_materia) VALUES (:matricula, :clave)");
                    $stmt->bindParam(':matricula', $matriculalumno);
                    $stmt->bindParam(':clave', $claveMateria);
                    $stmt->execute();
                    $materiasInsertadas++;

                } catch (PDOException $e) {
                    echo "Error al insertar asignación de materia: " . $e->getMessage();
                }
            } else {
                echo "No se pudo encontrar la clave de la materia: $materia <br>";
            }
        }
    }

    if ($materiasInsertadas == $totalMaterias) {
        echo "<script type='text/javascript'>alert('Las Materias Han Sido Asignadas.'); setTimeout(function(){window.location.href='ListaAlumnos.php?semestre=' + encodeURIComponent('$semestre') + '&carrera=' + encodeURIComponent('$carrera') + '&grupo=' + encodeURIComponent('$grupo');}, 100);</script>";
    }
}

//Esta Funcion se ocupa para ver si tal alumno ya tiene materias asignadas, si es asi, en la tabla de ListaAlumnos.php se da el mensaje que a ese alumno ya se le asigno materias 
function alumnoTieneMateriasAsignadas($matriculaAlumno) {
    $conexion = conectarDB();

    try {
        $stmt = $conexion->prepare("SELECT COUNT(*) as count FROM alum_mat WHERE Matricula_Alumno = :matricula");
        $stmt->bindParam(':matricula', $matriculaAlumno);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] > 0; // Retorna true si tiene materias asignadas, false si no
    } catch (PDOException $e) {
        echo "Error al consultar materias asignadas: " . $e->getMessage();
        return false;
    }
}


//Funcion que devuelve las materias que le han sido asignadas a los estudiantes este se ocupa en actualizarAsignacionMaterias.php
function  ObtenerMateriasAsignadas($matriculalumno) {
    $conexion = conectarDB();
    try {
        // Consulta SQL
        $sql = "SELECT a.Id, a.Clave_Materia,m.Nombre AS nombremateria
        FROM materias m
        JOIN alum_mat a ON m.Clave = a.Clave_Materia
        WHERE a.Matricula_Alumno = :matricula";
        // Preparar la declaración
        $statement = $conexion->prepare($sql);
        // Asociar valores a los marcadores de posición
        $statement->bindParam(':matricula', $matriculalumno, PDO::PARAM_STR);
        // Ejecutar la consulta
        $statement->execute();
        // Obtener los resultados
        $registros = $statement->fetchAll();
        return $registros;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}


function ObtenerMateriasFaltantes2($carrera,$semestre) {
    $conexion = conectarDB();

    try {
        $listamaterias = $conexion->prepare("SELECT * FROM materias WHERE Semestre <= :semestre AND ClaveFormacion = :formacion;");
        $listamaterias->bindParam(':formacion', $carrera, PDO::PARAM_STR);
        $listamaterias->bindParam(':semestre', $semestre, PDO::PARAM_STR);
        $listamaterias->execute();
        $resultados = $listamaterias->fetchAll(PDO::FETCH_ASSOC); // Convertir el resultado en un array asociativo
        return $resultados;

    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}



function ObtMateriasCorrAsig($carrera, $semestre, $matriculalumno) {
    // Obtener las materias asignadas al alumno
    $materiasAsignadas = ObtenerMateriasAsignadas($matriculalumno);

    // Obtener todas las materias disponibles
    $materiasDisponibles = ObtenerMateriasFaltantes2($carrera, $semestre);

    // Filtrar las materias disponibles para omitir las asignadas al alumno
    $materiasFaltantes = array_filter($materiasDisponibles, function($materia) use ($materiasAsignadas) {
        foreach ($materiasAsignadas as $asignada) {
            if ($materia['Clave'] === $asignada['Clave_Materia']) {
                return false; // Omitir esta materia porque ya está asignada al alumno
            }
        }
        return true; // Incluir esta materia porque no está asignada al alumno
    });

    return $materiasFaltantes;
}

function eliminarasignacionWhereMatricula($idasiganada) {
    $conexion = conectarDB();

    try {
        $sql = "DELETE FROM alum_mat WHERE Id = :idasignacion";
		$sql = $conexion->prepare($sql);
		$sql -> bindParam(':idasignacion', $idasiganada,PDO::PARAM_STR);
		$qryExecute = $sql->execute();
		if($qryExecute){
            header("refresh:1;url=PanelAlumnos.php");
            exit("Operación exitosa. Redirigiendo en 1 segundo...");
		}
		else{
            header("refresh:3;url=PanelAlumnos.php");
            exit("Ha ocurrido un error. Redirigiendo en tres segundos...");
        }
        return $registros;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}


function obtenerNombreApellido($numEmp) {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->prepare("SELECT Nombre, Apellidos FROM profesores WHERE NumEmp = :numEmp");
        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['Nombre'] . ' ' . $resultado['Apellidos'] : '';
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return '';
    } finally {
        $conexion = null;
    }
}



function baja($MatriculaTem, $MatriculaDef) {
    $conexion = conectarDB();

    try {
        $estatustem = "Baja Temporal";
        $estatusdef = "Baja Definitiva";
        if ($MatriculaTem == 0) { // AQUÍ ES PARA BAJA DEFINITIVA
            $sql = "UPDATE alumnos SET Estatus = :estatus WHERE Matricula = :Matricula";
            $sql = $conexion->prepare($sql);
            $sql->bindParam(':Matricula', $MatriculaDef, PDO::PARAM_STR);
            $sql->bindParam(':estatus', $estatusdef, PDO::PARAM_STR);
            $qryExecute = $sql->execute();
        } else { // AQUÍ PARA BAJA TEMPORAL
            $sql = "UPDATE alumnos SET Estatus = :estatus WHERE Matricula = :Matricula";
            $sql = $conexion->prepare($sql);
            $sql->bindParam(':Matricula', $MatriculaTem, PDO::PARAM_STR);
            $sql->bindParam(':estatus', $estatustem, PDO::PARAM_STR);
            $qryExecute = $sql->execute();
        }
        
        if ($qryExecute) {
            header("refresh:1;url=PanelAlumnos.php");
            return "Operación exitosa. Redirigiendo en 1 segundo...";
        } else {
            header("refresh:1;url=PanelAlumnos.php");
            return "Ha ocurrido un error. Redirigiendo en un segundo...";
        }
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function  obtenerCarreraid($carrera) {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->prepare("SELECT Nombre FROM formacion WHERE Clave = :carrera");
        $consulta->bindParam(':carrera', $carrera, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return null;
    } finally {
        $conexion = null;
    }
}



function obteneralumnosxcarreraEstTem($carrera) {
    $conexion = conectarDB();
    try {
        $estatus = "Baja Temporal";
        // Consulta SQL
        $sql = "select *from alumnos where formacion = :carrera and Estatus = :sts  ";
        $statement = $conexion->prepare($sql);
        // Asociar valores a los marcadores de posición
        $statement->bindParam(':carrera', $carrera, PDO::PARAM_STR);
        $statement->bindParam(':sts', $estatus, PDO::PARAM_STR);
        // Ejecutar la consulta
        $statement->execute();
        // Obtener los resultados
        $registros = $statement->fetchAll();
        

        return $registros;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function obteneralumnosxcarreraEstPer($carrera) {
    $conexion = conectarDB();
    try {
        $estatus = "Baja Definitiva";
        // Consulta SQL
        $sql = "select *from alumnos where formacion = :carrera and Estatus = :sts  ";
        $statement = $conexion->prepare($sql);
        // Asociar valores a los marcadores de posición
        $statement->bindParam(':carrera', $carrera, PDO::PARAM_STR);
        $statement->bindParam(':sts', $estatus, PDO::PARAM_STR);
        // Ejecutar la consulta
        $statement->execute();
        // Obtener los resultados
        $registros = $statement->fetchAll();
        

        return $registros;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}


function alta($Matricula) {
    $conexion = conectarDB();

    try {
        $estatus = "Activo";

            $sql = "UPDATE alumnos SET Estatus = :estatus WHERE Matricula = :Matricula";
            $sql = $conexion->prepare($sql);
            $sql->bindParam(':Matricula', $Matricula, PDO::PARAM_STR);
            $sql->bindParam(':estatus', $estatus, PDO::PARAM_STR);
            $qryExecute = $sql->execute();
        
        
        if ($qryExecute) {
            header("refresh:1;url=PanelAlumnos.php");
            return "Operación exitosa. Redirigiendo en 1 segundo...";
        } else {
            header("refresh:3;url=PanelAlumnos.php");
            return "Ha ocurrido un error. Redirigiendo en tres segundos...";
        }
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function obtenerNombreProfesor2($numEmp) {
    $conexion = conectarDB();

    try {
        $consulta = $conexion->prepare("SELECT Nombre, Apellidos FROM profesores WHERE NumEmp = :numEmp");
        $consulta->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        $consulta->execute();

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            return $resultado;  // Cambiado a return $resultado en lugar de return $resultado->fetchAll(PDO::FETCH_ASSOC)
        } else {
            return "Nombre no encontrado";
        }
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cierra la conexión en el bloque finally
        $conexion = null;
    }
}

function obtenerformaciond() {
    try {
        

        $conexion = conectarDB(); // Conecta a la base de datos
        $consulta = $conexion->query("SELECT Clave, Nombre FROM formacion"); // Consulta las carreras
        while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='{$row['Clave']}'>{$row['Nombre']}</option>";
        }
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
    
}

function obtener_grados_grupos() {
// Obtener la carrera seleccionada desde la solicitud GET
$carreraSeleccionada = $_GET['carrera'];

// Realizar la consulta en la base de datos para obtener los semestres y grupos de la carrera seleccionada
try {
    
    $conexion = conectarDB();

    $consulta = $conexion->prepare("SELECT DISTINCT a.Semestre, a.Grupo FROM alumnos a INNER JOIN cursar c ON a.Matricula = c.Matricula_Alumno INNER JOIN materias m ON c.Clave_Materia = m.Clave INNER JOIN formacion f ON m.ClaveFormacion = f.Clave WHERE f.Clave = :carrera");
    $consulta->bindParam(':carrera', $carreraSeleccionada, PDO::PARAM_STR);
    $consulta->execute();

    $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

    // Crear un array asociativo con los semestres y grupos
    $datos = array(
        'semestres' => array(),
        'grupos' => array()
    );

    foreach ($resultados as $row) {
        // Verificar si el semestre ya se ha agregado al array
        if (!in_array($row['Semestre'], $datos['semestres'])) {
            $datos['semestres'][] = $row['Semestre'];
        }

        // Verificar si el grupo ya se ha agregado al array
        if (!in_array($row['Grupo'], $datos['grupos'])) {
            $datos['grupos'][] = $row['Grupo'];
        }
    }

    // Devolver los datos en formato JSON
    echo json_encode($datos);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}  
}



// AGREGAR MATERIAS A PROFESORES



// Función para obtener la lista de materias
function obtenerListaMaterias() {

    $conexion = conectarDB();

    try {
        // Consulta SQL para obtener la lista de materias
        $sql = "SELECT Clave, Nombre FROM materias";
        
    
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $listaMaterias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Cerrar la conexión
        $conexion = null;
        
        return $listaMaterias;
    } catch (PDOException $e) {
        echo "Error al obtener la lista de materias: " . $e->getMessage();
        return array(); 
    }
}

function asociarMateriaProfesor($numEmp, $materias) {
    try {
        $conexion = conectarDB();

        // Verificar si el profesor existe
        $stmt = $conexion->prepare("SELECT NumEmp FROM profesores WHERE NumEmp = :numEmp");
        $stmt->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo "El profesor con el número de empleado $numEmp no existe.";
            return;
        }

        // Obtener el ciclo escolar activo
        $stmt = $conexion->prepare("SELECT Ciclo_Activo FROM ciclo_activo");
        $stmt->execute();
        $ciclo_escolar = $stmt->fetchColumn();

        if (!$ciclo_escolar) {
            echo "No se ha definido un ciclo escolar activo.";
            return;
        }

        // Asociar materias al profesor con el ciclo escolar activo
        foreach ($materias as $materia) {
            $stmt = $conexion->prepare("INSERT INTO prof_mat (NumEmp, Clave_Materia, Ciclo_Escolar) VALUES (:numEmp, :materia, :ciclo_escolar)");
            $stmt->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
            $stmt->bindParam(':materia', $materia, PDO::PARAM_STR);
            $stmt->bindParam(':ciclo_escolar', $ciclo_escolar, PDO::PARAM_STR);
            $stmt->execute();
        }

        echo "Materias asociadas al profesor correctamente.";
    } catch (PDOException $e) {
        echo "Error al asociar la materia al profesor: " . $e->getMessage();
    }
}


// Función para obtener la lista de formaciones
function obtenerListaFormaciones() {
    try {
  
        $conexion = conectarDB();
        $query = "SELECT Clave, Nombre FROM formacion";

        $statement = $conexion->prepare($query);
        $statement->execute();


        $formaciones = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $formaciones;
    } catch (PDOException $e) {
        echo "Error al obtener la lista de formaciones: " . $e->getMessage();
        return array(); 
    }


}


// Función para obtener la lista de materias asociadas a una formación específica
function obtenerMateriasPorFormacion($formacionClave) {
    try {
        $conexion = conectarDB();

        // Preparar la consulta para obtener las materias asociadas a la formación
        $stmt = $conexion->prepare("SELECT * FROM materias WHERE ClaveFormacion = :formacionClave");
        $stmt->bindParam(':formacionClave', $formacionClave, PDO::PARAM_INT);
        $stmt->execute();

        // Obtener y devolver el resultado como un array asociativo
        $materias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $materias;
    } catch (PDOException $e) {
        // Manejo de errores en caso de fallo en la conexión o consulta
        echo "Error al obtener las materias por formación: " . $e->getMessage();
        return array(); // Devolver un array vacío en caso de error
    }
}
    

function obtenerFormacionDeMaterias($materiasSeleccionadas) {
    $conexion = conectarDB();

    try {
        // Preparar la consulta para obtener la formación de las materias seleccionadas
        $sql = "SELECT DISTINCT ClaveFormacion FROM materias WHERE Clave IN ('" . implode("', '", $materiasSeleccionadas) . "')";

        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $formacion = $stmt->fetch(PDO::FETCH_ASSOC);

        // Cerrar la conexión
        $conexion = null;

        return $formacion ? $formacion['ClaveFormacion'] : null;
    } catch (PDOException $e) {
        echo "Error al obtener la formación de las materias: " . $e->getMessage();
        return null;
    }
}




// Función para obtener las materias asociadas a un profesor con información de la formación
function obtenerMateriasAsociadas($numEmp) {
    try {
        $conexion = conectarDB();

        $stmt = $conexion->prepare("SELECT pm.Id, m.Nombre AS Materia, f.Nombre AS Formacion, pm.Ciclo_Escolar 
                                    FROM prof_mat pm 
                                    JOIN materias m ON pm.Clave_Materia = m.Clave 
                                    JOIN formacion f ON m.ClaveFormacion = f.Clave 
                                    WHERE pm.NumEmp = :numEmp");
        $stmt->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    } catch (PDOException $e) {
        echo "Error al obtener las materias asociadas: " . $e->getMessage();
        return false;
    }
}


function eliminarMateriaProfesor($id) {
    try {
        $conexion = conectarDB();

        $stmt = $conexion->prepare("DELETE FROM prof_mat WHERE Id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo "Materia eliminada correctamente.";
    } catch (PDOException $e) {
        echo "Error al eliminar la materia del profesor: " . $e->getMessage();
    }
}

function obtenerMateriasAsociadas2($numEmp) {
    try {
        $conexion = conectarDB();

        // Almacenar el ciclo escolar activo en una variable
        $cicloActivo = obtenerCicloEscolarActivo();

        $stmt = $conexion->prepare("SELECT pm.Id, m.Clave AS ClaveMateria, m.Nombre AS Materia, f.Nombre AS Formacion 
                                    FROM prof_mat pm 
                                    JOIN materias m ON pm.Clave_Materia = m.Clave 
                                    JOIN formacion f ON m.ClaveFormacion = f.Clave 
                                    JOIN ciclo_activo ca ON pm.Ciclo_Escolar = ca.Ciclo_Activo
                                    WHERE pm.NumEmp = :numEmp AND ca.Ciclo_Activo = :cicloActivo");

        $stmt->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        // Pasar la variable $cicloActivo por referencia
        $stmt->bindParam(':cicloActivo', $cicloActivo, PDO::PARAM_STR);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    } catch (PDOException $e) {
        echo "Error al obtener las materias asociadas: " . $e->getMessage();
        return false;
    }
}


function obtenerCicloEscolarActivo() {
    try {
        $conexion = conectarDB();

        $stmt = $conexion->prepare("SELECT Ciclo_Activo FROM ciclo_activo LIMIT 1");
        $stmt->execute();

        $cicloActivo = $stmt->fetchColumn();

        return $cicloActivo;
    } catch (PDOException $e) {
        echo "Error al obtener el ciclo escolar activo: " . $e->getMessage();
        return false;
    }
}

function obtenerAlumnosPorMateria($claveMateria) {
    try {
        $conexion = conectarDB();

        $stmt = $conexion->prepare("SELECT alumnos.* FROM cursar JOIN alumnos ON cursar.Matricula_Alumno = alumnos.Matricula WHERE cursar.Clave_Materia = :claveMateria");
        $stmt->bindParam(':claveMateria', $claveMateria, PDO::PARAM_STR);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    } catch (PDOException $e) {
        echo "Error al obtener los alumnos que cursan la materia: " . $e->getMessage();
        return false;
    }
}

/*function obtenerDetallesAlumnosPorMateria($claveMateria) {
    try {
        $conexion = conectarDB();

        $stmt = $conexion->prepare("SELECT alumnos.*, cursar.Calificacion, cursar.Ciclo_Escolar FROM cursar JOIN alumnos ON cursar.Matricula_Alumno = alumnos.Matricula WHERE cursar.Clave_Materia = :claveMateria");
        $stmt->bindParam(':claveMateria', $claveMateria, PDO::PARAM_STR);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    } catch (PDOException $e) {
        echo "Error al obtener los detalles de los alumnos que cursan la materia: " . $e->getMessage();
        return false;
    }
}
()

*/
function ingresarCalificaciones($data, $claveMateria) {
    try {
        $conexion = conectarDB();

        // Obtener el ciclo escolar activo
        $stmtCiclo = $conexion->query("SELECT Ciclo_Activo FROM ciclo_activo");
        $cicloActivo = $stmtCiclo->fetch(PDO::FETCH_ASSOC)['Ciclo_Activo'];

        // Preparar la consulta de inserción
        $stmt = $conexion->prepare("INSERT INTO cursar (Matricula_Alumno, Clave_Materia, Calificacion, Ciclo_Escolar) VALUES (:matricula, :claveMateria, :calificacion, :cicloActivo)");

        // Iterar sobre las calificaciones recibidas y ejecutar la consulta de inserción
        foreach ($data as $matricula => $calificacion) {
            $stmt->bindParam(':matricula', $matricula, PDO::PARAM_INT);
            $stmt->bindParam(':claveMateria', $claveMateria, PDO::PARAM_STR);
            $stmt->bindParam(':calificacion', $calificacion, PDO::PARAM_STR);
            $stmt->bindParam(':cicloActivo', $cicloActivo, PDO::PARAM_STR);
            $stmt->execute();
        }

        return true;
    } catch (PDOException $e) {
        echo "Error al insertar las calificaciones: " . $e->getMessage();
        return false;
    }
}


//

function obtenerDetallesAlumnosPorMateria($claveMateria) {
    try {
        $conexion = conectarDB();

        // Consulta para obtener los detalles de los alumnos que cursan la materia
        $stmt = $conexion->prepare("SELECT alumnos.* FROM alumnos JOIN alum_mat ON alumnos.Matricula = alum_mat.Matricula_Alumno WHERE alum_mat.Clave_Materia = :claveMateria");
        $stmt->bindParam(':claveMateria', $claveMateria, PDO::PARAM_STR);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    } catch (PDOException $e) {
        echo "Error al obtener los detalles de los alumnos que cursan la materia: " . $e->getMessage();
        return false;
    }
}



//funciones de ciclos escolares 

function agregarCicloEscolar($ciclo_escolar) {
    try {
        $conexion = conectarDB();

        // Preparar la consulta para insertar el ciclo escolar
        $stmt = $conexion->prepare("INSERT INTO ciclos_escolares (ciclo) VALUES (:ciclo_escolar)");

        // Bind de los parámetros
        $stmt->bindParam(':ciclo_escolar', $ciclo_escolar);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si se agregó el ciclo escolar correctamente
        if ($stmt->rowCount() > 0) {
            return true; // Ciclo escolar agregado con éxito
        } else {
            return false; // Error al agregar ciclo escolar
        }
    } catch (PDOException $e) {
        echo "Error al agregar ciclo escolar: " . $e->getMessage();
        return false;
    }
}

function obtenerCiclosEscolares1() {
    try {
        $conexion = conectarDB();

        // Preparar consulta
        $stmt = $conexion->prepare("SELECT id, ciclo FROM ciclos_escolares");
        $stmt->execute();

        // Obtener resultados
        $ciclos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ciclos;
    } catch (PDOException $e) {
        echo "Error al obtener los ciclos escolares: " . $e->getMessage();
        return array();
    }
}

function activarCicloEscolar($idCiclo, $ciclo) {
    try {
        $conexion = conectarDB();

        // Eliminar cualquier ciclo escolar activo existente
        $stmt = $conexion->prepare("TRUNCATE TABLE ciclo_activo");
        $stmt->execute();

        // Insertar el nuevo ciclo escolar activo
        $stmt = $conexion->prepare("INSERT INTO ciclo_activo (Id, Ciclo_Activo) VALUES (:id, :ciclo)");
        $stmt->bindParam(':id', $idCiclo, PDO::PARAM_INT);
        $stmt->bindParam(':ciclo', $ciclo, PDO::PARAM_STR);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        echo "Error al activar el ciclo escolar: " . $e->getMessage();
        return false;
    }
}

function obtenerCicloEscolarPorId($idCiclo) {
    try {
        $conexion = conectarDB();

        $stmt = $conexion->prepare("SELECT id, ciclo FROM ciclos_escolares WHERE id = :id");
        $stmt->bindParam(':id', $idCiclo, PDO::PARAM_INT);
        $stmt->execute();
        $ciclo = $stmt->fetch(PDO::FETCH_ASSOC);

        return $ciclo;
    } catch (PDOException $e) {
        echo "Error al obtener el ciclo escolar por ID: " . $e->getMessage();
        return false;
    }
}



function aumentarSemestreAlumnos() {
    try {
        // Establecer conexión a la base de datos
        $conexion = conectarDB();

        // Consulta para actualizar el semestre solo en los registros con Semestre <= 12 y Estatus "Activo"

        $stmt = $conexion->prepare("UPDATE alumnos SET Semestre = Semestre + 1 WHERE Semestre <= 12 AND Estatus = 'Activo'");

        $stmt = $conexion->prepare("UPDATE alumnos SET Semestre = Semestre + 1 WHERE Semestre <= 13 AND Estatus = 'Activo'");


        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si se actualizaron los registros correctamente
        $numFilasActualizadas = $stmt->rowCount();
        if ($numFilasActualizadas > 0) {
            echo "Se ha aumentado el semestre en uno para $numFilasActualizadas registros con estatus 'Activo'.";
        } else {

            echo "No se encontraron registros con semestre menor o igual a 12 y estatus 'Activo' para actualizar.";

            echo "No se encontraron registros con semestre menor o igual a 13 y estatus 'Activo' para actualizar.";

          
        }

    } catch (PDOException $e) {
        echo "Error al aumentar el semestre de los alumnos: " . $e->getMessage();
    }
}
//Ocupado para la funcion generarPDFCalificacionesMateria
function ObtenerProfesorMateria($clave_materia) {
    $conexion = conectarDB();
    $sql = "SELECT p.Nombre, p.Apellidos FROM prof_mat pm 
            JOIN profesores p ON pm.NumEmp = p.NumEmp 
            JOIN materias m ON pm.Clave_Materia = m.Clave 
            WHERE m.Clave = :clave_materia;";

    $statement = $conexion->prepare($sql);
    $statement->bindParam(':clave_materia', $clave_materia, PDO::PARAM_STR);
    $statement->execute();
    $resultado = $statement->fetch(PDO::FETCH_ASSOC);

    return $resultado['Nombre'] . ' ' . $resultado['Apellidos'];
}



//// alumnos 

function borrarDatosAlumMat() {
    try {
        $conexion = conectarDB();

        // Preparar la consulta para borrar todos los datos de la tabla
        $stmt = $conexion->prepare("DELETE FROM alum_mat");

        // Ejecutar la consulta
        $stmt->execute();

        // Devolver verdadero si se ejecutó correctamente
        return true;
    } catch (PDOException $e) {
        // Capturar y mostrar cualquier error en caso de que ocurra
        echo "Error al borrar los datos de la tabla alum_mat: " . $e->getMessage();
        return false;
    }
}




// Función para obtener la contraseña de una secretaria específica
function obtenerContraseñaSecretaria($numEmp) {
    try {
        // Establecer la conexión con la base de datos
        $conexion = conectarDB();

        // Preparar la consulta SQL para obtener la contraseña de la secretaria
        $stmt = $conexion->prepare("SELECT Password FROM secretarias WHERE NumEmp = :numEmp");

        // Vincular el parámetro :numEmp
        $stmt->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

// Función para obtener la contraseña de una secretaria específica
function obtenerContraseñaSecretaria($numEmp) {
    try {
        // Establecer la conexión con la base de datos
        $conexion = conectarDB();

        // Preparar la consulta SQL para obtener la contraseña de la secretaria
        $stmt = $conexion->prepare("SELECT Password FROM secretarias WHERE NumEmp = :numEmp");

        // Vincular el parámetro :numEmp
        $stmt->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $contraseña = $stmt->fetchColumn();

        // Devolver la contraseña
        return $contraseña;
    } catch (PDOException $e) {
        // Manejar errores de la base de datos
        echo "Error al obtener la contraseña de la secretaria: " . $e->getMessage();
        return false;
    }
}


function obtenerNombreSecretaria($numEmp) {
    try {
        $conexion = conectarDB();
        $query = "SELECT Nombre, Apellido FROM secretarias WHERE NumEmp = :numEmp";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre = $resultado['Nombre'] . ' ' . $resultado['Apellido'];
        return $nombre;
    } catch (PDOException $e) {
        echo "Error al obtener el nombre de la secretaria: " . $e->getMessage();
        return false;
    }
}




function obtenerContraseñaProfesor($numEmp) {
    try {
        $conexion = conectarDB();

        // Preparar la consulta SQL
        $consulta = "SELECT Password FROM profesores WHERE NumEmp = :numEmp";

        // Preparar la declaración
        $stmt = $conexion->prepare($consulta);

        // Bind de parámetros
        $stmt->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Devolver la contraseña si se encontró, o null si no se encontró
        return $resultado ? $resultado['Password'] : null;
    } catch (PDOException $e) {
        // Manejar errores
        echo "Error al obtener la contraseña del profesor: " . $e->getMessage();
        return null;
    }
}


function obtenerContraseñaAdmin($usuario) {
    try {
        $conexion = conectarDB();

        // Preparar la consulta SQL
        $consulta = "SELECT Password FROM admin WHERE Usuario = :usuario";

        // Preparar la declaración
        $stmt = $conexion->prepare($consulta);

        // Bind de parámetros
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Devolver la contraseña si se encontró, o null si no se encontró
        return $resultado ? $resultado['Password'] : null;
    } catch (PDOException $e) {
        // Manejar errores
        echo "Error al obtener la contraseña del administrador: " . $e->getMessage();
        return null;
    }
}


function actualizarEstatusEgresados() {
    try {
        $conexion = conectarDB();

        // Obtener el ciclo activo
        $stmt = $conexion->prepare("SELECT Ciclo_Activo FROM ciclo_activo");
        $stmt->execute();
        $cicloActivo = $stmt->fetchColumn();

        // Seleccionar todas las matrículas de alumnos que cumplan con las condiciones y tengan al menos un registro en la tabla cursar
        $stmt = $conexion->prepare("SELECT Matricula FROM alumnos WHERE Estatus != 'Egresado' AND EXISTS (SELECT 1 FROM cursar WHERE cursar.Matricula_Alumno = alumnos.Matricula AND cursar.Ciclo_Escolar = :cicloActivo)");
        $stmt->bindParam(':cicloActivo', $cicloActivo, PDO::PARAM_STR);
        $stmt->execute();
        $matriculas = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($matriculas as $matriculaAlumno) {
            // Verificar si todas las materias asociadas al alumno cumplen con las condiciones y el ciclo escolar coincide con el ciclo activo
            $stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM cursar WHERE Matricula_Alumno = :matricula AND (Calificacion IS NULL OR Calificacion < 6) AND Ciclo_Escolar = :cicloActivo");
            $stmt->bindParam(':matricula', $matriculaAlumno, PDO::PARAM_INT);
            $stmt->bindParam(':cicloActivo', $cicloActivo, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['total'] == 0) {
                // Todas las materias tienen una calificación mayor o igual a 6 y el ciclo escolar coincide con el ciclo activo
                // Obtener el semestre del alumno
                $stmt = $conexion->prepare("SELECT Semestre FROM alumnos WHERE Matricula = :matricula");
                $stmt->bindParam(':matricula', $matriculaAlumno, PDO::PARAM_INT);
                $stmt->execute();
                $semestre = $stmt->fetchColumn();

                if ($semestre >= 9 && $semestre <= 13) {
                    // El semestre del alumno está entre 9 y 13
                    // Actualizar el estatus del alumno a "Egresado"
                    $stmt = $conexion->prepare("UPDATE alumnos SET Estatus = 'Egresado' WHERE Matricula = :matricula");
                    $stmt->bindParam(':matricula', $matriculaAlumno, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }

        return true;
    } catch (PDOException $e) {
        echo "Error al actualizar los estatus de los alumnos: " . $e->getMessage();
        return false;
    }
}






function obtenerMatricula() {
    // Suponiendo que la matrícula se envía desde un formulario POST
    if (isset($_POST['matricula_alumno'])) {
        return $_POST['matricula_alumno'];
    } else {
        // Si la matrícula no está presente en el formulario, puedes manejar el error o devolver un valor predeterminado
        return false;
    }
}

        // Obtener el resultado
        $contraseña = $stmt->fetchColumn();

        // Devolver la contraseña
        return $contraseña;
    } catch (PDOException $e) {
        // Manejar errores de la base de datos
        echo "Error al obtener la contraseña de la secretaria: " . $e->getMessage();
        return false;
    }
}

function obtenerNombreSecretaria($numEmp) {
    try {
        $conexion = conectarDB();
        $query = "SELECT Nombre, Apellido FROM secretarias WHERE NumEmp = :numEmp";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre = $resultado['Nombre'] . ' ' . $resultado['Apellido'];
        return $nombre;
    } catch (PDOException $e) {
        echo "Error al obtener el nombre de la secretaria: " . $e->getMessage();
        return false;
    }
}




function obtenerContraseñaProfesor($numEmp) {
    try {
        $conexion = conectarDB();

        // Preparar la consulta SQL
        $consulta = "SELECT Password FROM profesores WHERE NumEmp = :numEmp";

        // Preparar la declaración
        $stmt = $conexion->prepare($consulta);

        // Bind de parámetros
        $stmt->bindParam(':numEmp', $numEmp, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Devolver la contraseña si se encontró, o null si no se encontró
        return $resultado ? $resultado['Password'] : null;
    } catch (PDOException $e) {
        // Manejar errores
        echo "Error al obtener la contraseña del profesor: " . $e->getMessage();
        return null;
    }
}


function obtenerContraseñaAdmin($usuario) {
    try {
        $conexion = conectarDB();

        // Preparar la consulta SQL
        $consulta = "SELECT Password FROM admin WHERE Usuario = :usuario";

        // Preparar la declaración
        $stmt = $conexion->prepare($consulta);

        // Bind de parámetros
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Devolver la contraseña si se encontró, o null si no se encontró
        return $resultado ? $resultado['Password'] : null;
    } catch (PDOException $e) {
        // Manejar errores
        echo "Error al obtener la contraseña del administrador: " . $e->getMessage();
        return null;
    }
}

function eliminarGruposAlumnos($carrera, $estatus) {
    $conexion = conectarDB();

    try {
        
        $sql = "DELETE FROM alumnos WHERE formacion = :carrera AND Estatus = :estatus";
		$sql = $conexion->prepare($sql);
		$sql -> bindParam(':carrera', $carrera,PDO::PARAM_STR);
        $sql -> bindParam(':estatus', $estatus,PDO::PARAM_STR);
		$qryExecute = $sql->execute();
		if($qryExecute){
            header("refresh:1;url=PanelAlumnos.php");
            exit("Operación exitosa. Redirigiendo en 1 segundo...");
		}
		else{
            header("refresh:3;url=PanelAlumnos.php");
            exit("Ha ocurrido un error. Redirigiendo en tres segundos...");
        }
        return $registros;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function obteneralumnosegersados($carrera) {
    $conexion = conectarDB();
    try {
        $estatus = "Egresado";
        // Consulta SQL
        $sql = "select *from alumnos where formacion = :carrera and Estatus = :sts  ";
        $statement = $conexion->prepare($sql);
        // Asociar valores a los marcadores de posición
        $statement->bindParam(':carrera', $carrera, PDO::PARAM_STR);
        $statement->bindParam(':sts', $estatus, PDO::PARAM_STR);
        // Ejecutar la consulta
        $statement->execute();
        // Obtener los resultados
        $registros = $statement->fetchAll();
        

        return $registros;
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage(), 0);
        return false;
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}
?>



