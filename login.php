<?php
/**
 * User Validation Controller (Server-Side)
 * Validates submitted login name & password against the Database records.
 */

session_start();
require_once __DIR__ . '/db.php';

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.html");
    exit();
}

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Check for empty fields
if (empty($username) || empty($password)) {
    header("Location: failure.php?error=" . urlencode("Both Login Name and Password are required fields."));
    exit();
}

try {
    // Parameterized prepared query to prevent SQL Injection
    $stmt = $pdo->prepare("SELECT id, username, password, full_name, email, role, created_at FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    $is_authenticated = false;

    if ($user) {
        // Support direct match or secure password_verify (if hashed)
        if ($password === $user['password'] || password_verify($password, $user['password'])) {
            $is_authenticated = true;
        }
    }

    if ($is_authenticated) {
        // Regenerate session ID to prevent session fixation attacks
        session_regenerate_id(true);

        // Store user state in active session
        $_SESSION['authenticated'] = true;
        $_SESSION['user_id']       = $user['id'];
        $_SESSION['username']      = $user['username'];
        $_SESSION['full_name']     = $user['full_name'];
        $_SESSION['email']         = $user['email'];
        $_SESSION['role']          = $user['role'];
        $_SESSION['created_at']    = $user['created_at'];
        $_SESSION['login_time']    = date("d-m-Y h:i:s A");

        // Return successful login page
        header("Location: success.php");
        exit();
    } else {
        // Authentication failed - return failure view with informative message
        header("Location: failure.php?username=" . urlencode($username) . "&error=" . urlencode("Invalid Login Name or Password. Please verify your credentials and try again."));
        exit();
    }
} catch (PDOException $e) {
    header("Location: failure.php?error=" . urlencode("Database lookup error: " . $e->getMessage()));
    exit();
}
?>
