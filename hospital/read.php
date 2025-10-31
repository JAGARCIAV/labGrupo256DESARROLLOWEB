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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestión de Médicos</title>

<style>
body { font-family: 'Arial', sans-serif; margin:0; background-color: #eef4f7; display:flex; }

.sidebar {
    width: 220px; background-color: #023e8a; height: 100vh; color:white; padding:20px 0; position:fixed;
}
.sidebar h2 { text-align:center; margin-bottom:30px; }
.sidebar a { display:block; padding:12px 20px; color:white; text-decoration:none; font-weight:bold; transition: background .2s; }
.sidebar a:hover { background-color:#0077b6; }

.main-content { margin-left:230px; width: calc(100% - 230px); padding:25px; }

header { background:#0077b6; color:#fff; padding:15px; border-radius:8px; margin-bottom:20px; }

table { width:100%; background:white; border-collapse:collapse; border-radius:10px; overflow:hidden; }
th, td { padding:12px; border-bottom:1px solid #ddd; text-align:left; }
th { background:#0096c7; color:white; }
tr:hover { background-color:#caf0f8; }

.btn { padding:6px 10px; border-radius:5px; color:white; font-size:14px; text-decoration:none; cursor:pointer; }
.btn-editar { background:#0096c7; }
.btn-eliminar { background:#d90429; }
.btn-insertar { background:#2b9348; margin-bottom:15px; display:inline-block; }

/* Modal */
.modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:2000; }
.modal-content { background:#fff; padding:25px; width:420px; border-radius:12px; box-shadow:0 0 15px rgba(0,0,0,0.3); text-align:center; }
.modal-content input { width:90%; padding:10px; margin:8px 0; border-radius:6px; border:1px solid #ccc; }
.btn-guardar { background:#2b9348; border:none; color:white; padding:10px; width:90%; border-radius:6px; cursor:pointer; margin-top:10px; font-size:16px; font-weight:bold; }
.cerrar { float:right; font-size:22px; cursor:pointer; font-weight:bold; }
.mensaje { font-weight:bold; margin-top:10px; display:none; padding:10px; border-radius:5px; text-align:center; }
</style>
</head>

<body>

<div class="sidebar">
    <h2>HOSPITAL</h2>
    <a href="read.php">👨‍⚕️ Médicos</a>
    <a href="pacientes/read_pacientes.php">🧑 Pacientes</a>
    <a href="read_citas.php">📅 Citas</a>

    <?php if ($_SESSION['rol'] === 'admin') { ?>
        <a href="read_users.php">👥 Usuarios</a>
    <?php } ?>

    <a href="cerrar.php">🚪 Cerrar Sesión</a>
</div>

<div class="main-content">
<header>
    <h1>Listado de Médicos 🏥</h1>
</header>

<?php if ($_SESSION['rol'] === 'admin') { ?>
<button onclick="abrirModalInsertar();" class="btn btn-insertar">➕ Nuevo Médico</button>
<?php } ?>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Especialidad</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <?php if ($_SESSION['rol'] === 'admin') { ?>
            <th>Acciones</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody id="tabla-medicos"></tbody>
</table>
</div>

<!-- Modal Insertar -->
<div id="modalInsertar" class="modal">
    <div class="modal-content">
        <span class="cerrar" onclick="cerrarModalInsertar()">&times;</span>
        <h2>Registrar Nuevo Médico</h2>

        <form id="formInsertar">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="especialidad" placeholder="Especialidad" required>
            <input type="text" name="telefono" placeholder="Teléfono" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <button type="submit" class="btn-guardar">Guardar</button>
        </form>

        <div id="mensajeInsertar" class="mensaje"></div>
    </div>
</div>

<!-- Modal Editar -->
<div id="modalEditar" class="modal">
    <div class="modal-content" id="contenidoEditar">
        <!-- Se carga dinámicamente form_editar_medico.php -->
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // Cargar médicos
    async function cargarMedicos() {
        const res = await fetch("get_medicos.php");
        const data = await res.json();
        const tabla = document.querySelector("#tabla-medicos");
        tabla.innerHTML = "";

        data.forEach(m => {
            const tr = document.createElement("tr");
            tr.id = "fila-" + m.id;
            tr.innerHTML = `
                <td>${m.nombre}</td>
                <td>${m.especialidad}</td>
                <td>${m.telefono}</td>
                <td>${m.correo}</td>
                <?php if ($_SESSION['rol'] === 'admin') { ?>
                <td>
                    <button class="btn btn-editar" onclick="abrirModalEditar(${m.id})">Editar</button>
                    <button class="btn btn-eliminar" onclick="eliminarMedico(${m.id})">Eliminar</button>
                </td>
                <?php } ?>
            `;
            tabla.appendChild(tr);
        });
    }

    cargarMedicos();

    // Modal Insertar
    function abrirModalInsertar(){ document.getElementById("modalInsertar").style.display="flex"; }
    function cerrarModalInsertar(){ document.getElementById("modalInsertar").style.display="none"; }

    // Insertar médico
    const formInsertar = document.getElementById("formInsertar");
    formInsertar.addEventListener("submit", async function(e){
        e.preventDefault();
        console.log("Formulario enviado"); // Para depuración

        const formData = new FormData(this);
        const res = await fetch("insertar_medico.php",{ method:"POST", body: formData });
        const data = await res.json();
        const msg = document.getElementById("mensajeInsertar");
        msg.style.display="block";

        if(data.status==="success"){
            msg.style.backgroundColor="#d4edda";
            msg.style.color="#155724";
            msg.textContent="✅ "+data.message;
            this.reset();
            cargarMedicos();
            setTimeout(()=>{ msg.style.display="none"; cerrarModalInsertar(); },1500);
        } else {
            msg.style.backgroundColor="#f8d7da";
            msg.style.color="#721c24";
            msg.textContent="❌ "+data.message;
        }
    });

    // Modal Editar
    window.abrirModalEditar = async function(id){
        const modal = document.getElementById("modalEditar");
        const contenido = document.getElementById("contenidoEditar");

        try {
            const res = await fetch(`form_editar_medico.php?id=${id}`);
            const html = await res.text();
            contenido.innerHTML = html;
            modal.style.display="flex";

            const formEditar = contenido.querySelector("#formEditar");
            const cerrar = contenido.querySelector(".cerrar");
            const mensaje = contenido.querySelector("#mensaje");

            cerrar.addEventListener("click", ()=> modal.style.display="none");

            formEditar.addEventListener("submit", async function(e){
                e.preventDefault();
                const formData = new FormData(this);
                const resUpdate = await fetch("update_medico.php",{ method:"POST", body: formData });
                const data = await resUpdate.json();
                mensaje.style.display="block";

                if(data.status==="success"){
                    mensaje.style.backgroundColor="#d4edda";
                    mensaje.style.color="#155724";
                    mensaje.textContent="✅ "+data.message;
                    cargarMedicos();
                    setTimeout(()=> modal.style.display="none",1500);
                } else {
                    mensaje.style.backgroundColor="#f8d7da";
                    mensaje.style.color="#721c24";
                    mensaje.textContent="❌ "+data.message;
                }
            });

        } catch(error){
            console.error(error);
            alert("Error al cargar el formulario de edición.");
        }
    }

    // Eliminar médico
    window.eliminarMedico = async function(id){
        if(!confirm("¿Seguro que deseas eliminar este médico?")) return;

        try {
            const res = await fetch(`delete_medico.php?id=${id}`);
            const data = await res.json();
            if(data.status==="success"){
                const fila = document.getElementById("fila-"+id);
                if(fila) fila.remove();
            } else {
                alert(data.message);
            }
        } catch(error){
            console.error(error);
            alert("Error al eliminar médico");
        }
    }

    window.abrirModalInsertar = abrirModalInsertar;
    window.cerrarModalInsertar = cerrarModalInsertar;
});
</script>

</body>
</html>
