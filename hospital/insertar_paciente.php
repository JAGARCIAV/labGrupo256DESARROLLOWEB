<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("proteger.php"); // Asegura que el usuario esté logueado
include("conexion.php");  // Conexión a la base de datos

// Solo admin puede insertar
if ($_SESSION['rol'] !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'No tienes permisos para realizar esta acción.'
    ]);
    exit;
}

// Verificamos que sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $documento = trim($_POST['documento'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');

    if(empty($nombre) || empty($documento) || empty($telefono) || empty($correo)){
        echo json_encode([
            'status'=>'error',
            'message'=>'Todos los campos son obligatorios.'
        ]);
        exit;
    }

    // Verificar correo único (opcional)
    $stmtCheck = $con->prepare("SELECT id FROM pacientes WHERE correo=?");
    $stmtCheck->bind_param("s", $correo);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    if($resultCheck->num_rows > 0){
        echo json_encode([
            'status'=>'error',
            'message'=>'El correo ya está registrado.'
        ]);
        exit;
    }

    // Insertar paciente
    $stmt = $con->prepare("INSERT INTO pacientes (nombre, documento, telefono, correo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $documento, $telefono, $correo);

    if($stmt->execute()){
        echo json_encode([
            'status'=>'success',
            'message'=>'Paciente registrado correctamente.'
        ]);
    } else {
        echo json_encode([
            'status'=>'error',
            'message'=>'Error al registrar paciente: '.$con->error
        ]);
    }

} else {
    echo json_encode([
        'status'=>'error',
        'message'=>'Método no permitido.'
    ]);
}
