<?php
header("Content-Type: application/json; charset=UTF-8");
include("conexion.php");

$sql = "SELECT id, nombre, documento, telefono, correo FROM pacientes";
$result = $con->query($sql);

$medicos = [];

while ($row = $result->fetch_assoc()) {
    $medicos[] = $row;
}

echo json_encode($medicos);
$con->close();
?>
