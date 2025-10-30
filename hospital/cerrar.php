<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Si se desea borrar la cookie de sesión (opcional)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente destruir la sesión
session_destroy();

// Redirigir al login o página principal
echo "Ha finalizado la sesión correctamente.";
echo '<meta http-equiv="refresh" content="2;url=from_login.html">';
exit();
?>
