<?php 
session_start();
include("proteger.php");
include("conexion.php");

$sql = "SELECT id, nombre, especialidad, telefono, correo FROM medicos";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Médicos</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f8fb;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #0077b6;
            color: white;
            text-align: center;
            padding: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .usuario-info {
            background-color: #e1f5fe;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #0077b6;
        }

        .usuario-info span {
            font-weight: bold;
            color: #0077b6;
        }

        main {
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #0077b6;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color: #00b4d8;
            color: white;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f1faff;
        }

        tr:hover {
            background-color: #caf0f8;
        }

        a.btn {
            text-decoration: none;
            color: white;
            background-color: #0077b6;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 14px;
            transition: 0.2s;
        }

        a.btn:hover {
            background-color: #005f8a;
        }

        a.btn-editar {
            background-color: #0096c7;
        }

        a.btn-eliminar {
            background-color: #e63946;
        }

        a.btn-insertar {
            display: inline-block;
            margin-top: 15px;
            background-color: #38b000;
            text-align: center;
        }

        a.btn-insertar:hover {
            background-color: #2b8a00;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 15px;
            background-color: #0077b6;
            color: white;
        }
    </style>
</head>
<body>

<header>
    <h1>🏥 Sistema de Gestión de Médicos</h1>
</header>

<div class="usuario-info">
    <div>Usuario: <span><?php echo $_SESSION['email']; ?></span></div>
    <div>Nivel: <span><?php echo $_SESSION['rol']; ?></span></div>
</div>

<main>
    <h2>Listado de Médicos</h2>

    <table>
        <tr>
            <th>Nombre</th>
            <th>Especialidad</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Operaciones</th>
        </tr>

        <?php while($row = mysqli_fetch_array($result)) { ?>
        <tr>
            <td><?php echo $row['nombre']; ?></td>
            <td><?php echo $row['especialidad']; ?></td>
            <td><?php echo $row['telefono']; ?></td>
            <td><?php echo $row['correo']; ?></td>
    <td>
      <?php if ($_SESSION['rol']=='admin'){ ?>
      <a href="../config-amigos/from_edit-amigos.php?id=<?php echo $row['id']; ?>" onclick="return confirm('¿Seguro que deseas editar este usuario?')">Editar</a>
      <a href="../config-amigos/delete-amigos.php?id=<?php echo $row['id']; ?>" onclick="return confirm('¿Seguro que quieres eliminar este registro?');">Eliminar</a>
      <?php } ?>
    </td>
        </tr>
        <?php } 
        $con->close();
        ?>
    </table>

    <a href="from_insertar.html" class="btn btn-insertar">➕ Insertar nuevo médico</a>
</main>

<footer>
    © 2025 Hospital Central — Sistema Médico
</footer>

</body>
</html>
