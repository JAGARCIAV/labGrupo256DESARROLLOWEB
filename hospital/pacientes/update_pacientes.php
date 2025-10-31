<?php
include("conexion.php");

if (!isset($_POST['id'], $_POST['nombre'], $_POST['documento'], $_POST['telefono'], $_POST['correo'])) {
    echo json_encode(["status"=>"error","message"=>"Faltan datos requeridos"]);
    exit;
}

$id = $_POST['id'];
$nombre = trim($_POST['nombre']);
$especialidad = trim($_POST['documento']);
$telefono = trim($_POST['telefono']);
$correo = trim($_POST['correo']);

$sql = "UPDATE pacientes SET nombre=?, documento=?, telefono=?, correo=? WHERE id=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ssssi", $nombre, $documento, $telefono, $correo, $id);

if($stmt->execute()){
    echo json_encode(["status"=>"success","message"=>"Médico actualizado correctamente"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Error al actualizar en la base de datos"]);
}

$stmt->close();
$con->close();
?>
