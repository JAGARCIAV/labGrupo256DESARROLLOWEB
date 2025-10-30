<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("proteger.php");
include("conexion.php");

// Validar que llegue el ID
if(!isset($_GET['id'])){
    die("ID del médico no proporcionado.");
}

$id = $_GET['id'];

// Obtener datos del médico
$stmt = $con->prepare("SELECT * FROM medicos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$medico = $result->fetch_assoc();

if(!$medico){
    die("Médico no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Médico</title>
<style>
    body { font-family: Arial; background-color: #eef4f7; padding: 30px; }
    .container { background: #fff; padding: 25px; border-radius: 10px; max-width: 500px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.2); }
    input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
    button { background: #0096c7; color: white; border: none; padding: 10px; width: 100%; border-radius: 5px; cursor: pointer; }
    button:hover { background: #0077b6; }
    .mensaje { padding: 10px; border-radius: 5px; font-weight: bold; display: none; margin-top: 10px; text-align: center; }
</style>
</head>
<body>

<div class="container">
    <h2>Editar Médico</h2>
    <form id="formEditar">
        <input type="hidden" name="id" value="<?= $medico['id'] ?>">
        <input type="text" name="nombre" placeholder="Nombre" value="<?= htmlspecialchars($medico['nombre']) ?>" required>
        <input type="text" name="especialidad" placeholder="Especialidad" value="<?= htmlspecialchars($medico['especialidad']) ?>" required>
        <input type="number" name="telefono" placeholder="Teléfono" value="<?= htmlspecialchars($medico['telefono']) ?>" required>
        <input type="email" name="correo" placeholder="Correo" value="<?= htmlspecialchars($medico['correo']) ?>" required>
        <button type="submit">Actualizar Médico</button>
    </form>

    <div id="mensaje" class="mensaje"></div>
</div>

<script>
document.getElementById("formEditar").addEventListener("submit", async function(e){
    e.preventDefault();

    const formData = new FormData(this);

    // Validar que todos los campos estén completos
    const requiredFields = ["id","nombre","especialidad","telefono","correo"];
    for(let field of requiredFields){
        if(!formData.get(field) || formData.get(field).trim() === ""){
            alert("Todos los campos son obligatorios");
            return;
        }
    }

    try {
        const res = await fetch("update_medico.php", {
            method: "POST",
            body: formData
        });
        const data = await res.json();
        const msg = document.getElementById("mensaje");
        msg.style.display = "block";

        if(data.status === "success"){
            msg.style.backgroundColor = "#d4edda";
            msg.style.color = "#155724";
            msg.textContent = "✅ " + data.message;

            // Redirigir automáticamente al listado después de 1.5s
            setTimeout(()=>{
                window.location.href = "read.php";
            },1500);

        } else {
            msg.style.backgroundColor = "#f8d7da";
            msg.style.color = "#721c24";
            msg.textContent = "❌ " + data.message;
        }

    } catch (error) {
        console.error(error);
        alert("Ocurrió un error al actualizar el médico");
    }
});
</script>

</body>
</html>
