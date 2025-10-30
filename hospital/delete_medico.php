<?php
include("conexion.php");

if (!isset($_GET['id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "ID no proporcionado"
    ]);
    exit;
}

$id = $_GET['id'];

$sql = "DELETE FROM medicos WHERE id=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Registro eliminado"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error en la base de datos"]);
}

$stmt->close();
$con->close();
?>
