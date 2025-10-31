<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("proteger.php");
include("conexion.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestión de Pacientes</title>

<style>
body { font-family: Arial, sans-serif; margin:0; background-color: #eef4f7; display:flex; }
.sidebar { width: 220px; background-color: #023e8a; height: 100vh; color:white; padding:20px 0; position:fixed; }
.sidebar h2 { text-align:center; margin-bottom:30px; }
.sidebar a { display:block; padding:12px 20px; color:white; text-decoration:none; font-weight:bold; }
.sidebar a:hover { background:#0077b6; }
.main-content { margin-left:230px; padding:25px; width: calc(100% - 230px); }
header { background:#0077b6; color:white; padding:15px; border-radius:8px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; }
table { width:100%; background:white; border-collapse:collapse; border-radius:10px; overflow:hidden; }
th, td { padding:12px; border-bottom:1px solid #ddd; text-align:left; }
th { background:#0096c7; color:white; }
tr:hover { background:#caf0f8; }
button { cursor:pointer; padding:7px 12px; border:none; border-radius:5px; }
.btn-insertar { background:#38b000; color:white; }
.btn-editar { background:#0077b6; color:white; }
.btn-eliminar { background:#d00000; color:white; }

.modal {
  display:none; position:fixed; top:0; left:0; width:100%; height:100%;
  background:rgba(0,0,0,0.75); justify-content:center; align-items:center;
}
.modal-content {
  background:white; padding:20px; border-radius:10px; width:340px;
}
.mensaje {
  background:#38b000; color:white; text-align:center; padding:10px;
  border-radius:5px; margin-top:10px; display:none;
}
.cerrar { float:right; cursor:pointer; font-size:20px; }
</style>

</head>
<body>

<div class="sidebar">
    <h2>HOSPITAL</h2>
    <a href="read.php">👨‍⚕️ Médicos</a>
    <a href="read_pacientes.php">🧑 Pacientes</a>
    <a href="read_citas.php">📅 Citas</a>

    <?php if ($_SESSION['rol'] === 'admin') { ?>
        <a href="read_users.php">👥 Usuarios</a>
    <?php } ?>

    <a href="cerrar.php">🚪 Cerrar Sesión</a>
</div>

<div class="main-content">
<header>
    <h1>Pacientes 🧑‍🦽</h1>
    <?php if ($_SESSION['rol'] === 'admin') { ?>
    <button class="btn-insertar" onclick="abrirInsertar()">+ Nuevo Paciente</button>
    <?php } ?>
</header>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Documento</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <?php if ($_SESSION['rol'] === 'admin') { ?>
            <th>Acciones</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody id="tabla-pacientes"></tbody>
</table>
</div>

<!-- Modal Insertar -->
<div id="modalInsertar" class="modal">
<div class="modal-content">
<span class="cerrar" onclick="cerrarInsertar()">&times;</span>
<h2>Registrar Paciente</h2>
<form id="formInsertar">
    <input type="text" name="nombre" placeholder="Nombre" required><br><br>
    <input type="text" name="documento" placeholder="Documento" required><br><br>
    <input type="number" name="telefono" placeholder="Teléfono" required><br><br>
    <input type="email" name="correo" placeholder="Correo" required><br><br>
    <button type="submit" class="btn-insertar">Guardar</button>
</form>
<div id="msgInsertar" class="mensaje"></div>
</div>
</div>

<!-- Modal Editar -->
<div id="modalEditar" class="modal">
<div class="modal-content" id="editarContent">
</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", cargarPacientes);

async function cargarPacientes() {
    const res = await fetch("get_pacientes.php");
    const data = await res.json();
    const tabla = document.getElementById("tabla-pacientes");
    tabla.innerHTML = "";

    data.forEach(p => {
        const tr = document.createElement("tr");
        
        tr.innerHTML = `
            <td>${p.nombre}</td>
            <td>${p.documento}</td>
            <td>${p.telefono}</td>
            <td>${p.correo}</td>
            <?php if ($_SESSION['rol'] === 'admin') { ?>
            <td>
                <button class='btn-editar' onclick="editar(${p.id})">Editar</button>
                <button class='btn-eliminar' onclick="eliminar(${p.id})">Eliminar</button>
            </td>
            <?php } ?>
        `;

        tabla.appendChild(tr);
    });
}

function abrirInsertar() {
    document.getElementById("modalInsertar").style.display = "flex";
}
function cerrarInsertar() {
    document.getElementById("modalInsertar").style.display = "none";
}

document.getElementById("formInsertar").addEventListener("submit", async (e) => {
    e.preventDefault();
    let datos = new FormData(e.target);

    const res = await fetch("insertar_paciente.php", {
        method: "POST",
        body: datos
    });

    let msg = await res.text();
    document.getElementById("msgInsertar").style.display = "block";
    document.getElementById("msgInsertar").innerText = msg;

    setTimeout(() => { 
        location.reload(); 
    }, 1200);
});

// EDITAR
async function editar(id) {
    const res = await fetch("editar_paciente.php?id=" + id);
    const html = await res.text();
    document.getElementById("editarContent").innerHTML = html;
    document.getElementById("modalEditar").style.display = "flex";
}

document.addEventListener("click", function(e){
    if(e.target.classList.contains("cerrar")){
        document.getElementById("modalEditar").style.display = "none";
    }
});

// ELIMINAR
async function eliminar(id) {
    if(!confirm("¿Seguro de eliminar?")) return;
    const res = await fetch("delete_paciente.php?id=" + id);
    const msg = await res.text();
    alert(msg);
    cargarPacientes();
}
</script>

</body>
</html>
