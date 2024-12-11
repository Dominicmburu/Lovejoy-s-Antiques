<?php
$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => $cookieParams["lifetime"],
    'path' => $cookieParams["path"],
    'domain' => $cookieParams["domain"],
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', 
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
