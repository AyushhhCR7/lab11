<?php
/**
 * Logout Handler
 * Destroys active user session and redirects back to the login page.
 */

session_start();

// Unset all session variables
$_SESSION = [];

// Delete session cookie if present
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy session
session_destroy();

// Redirect with success message
header("Location: index.html?message=" . urlencode("You have been successfully logged out."));
exit();
?>
