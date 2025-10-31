<?php
header('Content-Type: application/json');
include("conexion.php"); // Asegúrate que aquí defines $con

// Verificar que sea POST
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $especialidad = isset($_POST['especialidad']) ? trim($_POST['especialidad']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';

    // Validar campos
    if(empty($nombre) || empty($especialidad) || empty($telefono) || empty($correo)){
        echo json_encode(['status'=>'error','message'=>'Todos los campos son obligatorios.']);
        exit;
    }

    if(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
        echo json_encode(['status'=>'error','message'=>'El correo no es válido.']);
        exit;
    }

    try {
        // Preparar la consulta con prepared statement
        $stmt = $con->prepare("INSERT INTO medicos (nombre, especialidad, telefono, correo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $especialidad, $telefono, $correo);

        if($stmt->execute()){
            echo json_encode(['status'=>'success','message'=>'Médico guardado exitosamente.']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Error al guardar el médico.']);
        }

        $stmt->close();
        $con->close();

    } catch(Exception $e){
        echo json_encode(['status'=>'error','message'=>'Ocurrió un error: '.$e->getMessage()]);
    }

} else {
    echo json_encode(['status'=>'error','message'=>'Método no permitido.']);
}
