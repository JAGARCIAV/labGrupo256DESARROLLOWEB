<?php
session_start();
include("conexion.php");

// Validar que se recibieron los datos
if (!isset($_POST['email'], $_POST['password'])) {
    die("Faltan datos de autenticación.");
}

$email = trim($_POST['email']);
$password = sha1($_POST['password']); // ⚠️ SHA1 no es seguro, se recomienda password_hash() y password_verify()

// Consulta preparada segura
$sql = "SELECT correo, rol FROM usuarios WHERE correo = ? AND password = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Guardar sesión del usuario
    $_SESSION['email'] = $user['correo'];
    $_SESSION['rol'] = $user['rol'];

    // Redirigir a página principal
    header("Location: read.php");
    exit();
} else {
    echo "<p style='color:red;'>Error: datos de autenticación incorrectos.</p>";
    echo '<meta http-equiv="refresh" content="3;url=form-login.html">';
}
?>
