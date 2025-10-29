<?php session_start();
include("proteger.php");
include("conexion.php");
$sql="SELECT id,nombre,especialidad,telefono,correo FROM medicos";
$result=$con->query($sql);
?>

<div>
    usuario:<?php  echo $_SESSION['email'];?>
    nivel:<?php  echo $_SESSION['rol'];?>
    

</div>



<table border="1">
    <tr>
        <th>Nombres</th>
        <th>Especialidad</th>
        <th>Telefono</th>
        <th>Correo</th>
        <th>Operaciones</th>
    </tr>

<?php
    while($row=mysqli_fetch_array($result)){
?>
    <tr>
        <td><?php echo $row['nombre'];?></td>  
        <td><?php echo $row['especialidad'];?></td>
        <td><?php echo $row['telefono'];?> </td>
        <td><?php echo $row['correo'];?></td>
        
        <td>
            <a href="from_Edit.php?id=<?php echo $row['id']?>">Editar</a>
            <a href="delete.php?id=<?php echo $row['id']?>">Eliminar</a>

        </td>
    </tr>
    <?php
    }
    $con->close();
    ?> 
</table>
<a href="from_insertar.html">Insertar</a>