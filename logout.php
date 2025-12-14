<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Delete the session cookie if set
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Optionally clear client-side sessionStorage via redirect param (handled on page)

// Redirect to auth page
header('Location: auth.php');
exit;
