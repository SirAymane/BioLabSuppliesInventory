<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Si l'usuari no té sessió iniciada, redirigeix a la pàgina principal
    header('Location: index.php');
    exit();
}

// Destruir la sessió i esborrar la cookie de sessió del navegador del client
$_SESSION = array();
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

// Redirigir a la pàgina principal
header('Location: index.php');
exit();
?>
