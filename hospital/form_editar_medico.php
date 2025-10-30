<?php
include("conexion.php");

$id = $_GET['id'];
$stmt = $con->prepare("SELECT * FROM medicos WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$medico = $stmt->get_result()->fetch_assoc();
?>

<span class="cerrar" style="float:right; cursor:pointer;">&times;</span>
<h2>Editar Médico</h2>
<form id="formEditar">
    <input type="hidden" name="id" value="<?= $medico['id'] ?>">
    <input type="text" name="nombre" value="<?= htmlspecialchars($medico['nombre']) ?>" required>
    <input type="text" name="especialidad" value="<?= htmlspecialchars($medico['especialidad']) ?>" required>
    <input type="number" name="telefono" value="<?= htmlspecialchars($medico['telefono']) ?>" required>
    <input type="email" name="correo" value="<?= htmlspecialchars($medico['correo']) ?>" required>
    <button type="submit">Actualizar</button>
</form>
<div id="mensaje" class="mensaje"></div>
