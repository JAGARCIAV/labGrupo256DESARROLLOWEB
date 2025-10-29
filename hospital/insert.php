<?php
include("conexion.php");
$nombre=$_POST["nombre"];
$especialidad=$_POST["especialidad"];
$sexo=$_POST["sexo"];
$numero_documento=$_POST["numero_documento"];
$sql= "INSERT INTO padron(nombre, apellidos, sexo, numero_documento) value(?,?,?,?)";
$stmt=$con->prepare($sql);
$stmt->bind_param("ssss",$nombre,$apellidos,$sexo,$numero_documento);
if( $stmt->execute() ){
    echo "Registro exitoso";
}
?>
<meta http-equiv="refresh" content="0; url=read.php">