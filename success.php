<?php
/**
 * Successful Login Page
 * Displayed when user credentials match database records.
 */

session_start();

// Guard check: verify authenticated session
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: index.html?error=" . urlencode("Access Denied: Please log in first."));
    exit();
}

$username   = htmlspecialchars($_SESSION['username'] ?? 'User');
$full_name  = htmlspecialchars($_SESSION['full_name'] ?? 'User');
$email      = htmlspecialchars($_SESSION['email'] ?? 'N/A');
$role       = htmlspecialchars($_SESSION['role'] ?? 'Standard User');
$user_id    = htmlspecialchars($_SESSION['user_id'] ?? '1');
$login_time = htmlspecialchars($_SESSION['login_time'] ?? date("d-m-Y h:i:s A"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Successful &bull; User Validation System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container wide-container">
        <div class="card">
            <!-- Animated Success Icon -->
            <div class="status-icon-wrapper status-icon-success">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <div class="card-header">
                <span class="badge badge-success">&check; Authentication Verified</span>
                <h1>Login Successful!</h1>
                <p class="subtitle">Welcome back, <strong><?php echo $full_name; ?></strong>. Your credentials match the database record.</p>
            </div>

            <!-- Profile Summary Card -->
            <div class="profile-card">
                <div class="profile-row">
                    <span class="profile-label">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Login Name (Username)
                    </span>
                    <span class="profile-value"><?php echo $username; ?></span>
                </div>

                <div class="profile-row">
                    <span class="profile-label">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Full Name
                    </span>
                    <span class="profile-value"><?php echo $full_name; ?></span>
                </div>

                <div class="profile-row">
                    <span class="profile-label">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Email Address
                    </span>
                    <span class="profile-value"><?php echo $email; ?></span>
                </div>

                <div class="profile-row">
                    <span class="profile-label">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Role / Designation
                    </span>
                    <span class="profile-value"><?php echo $role; ?></span>
                </div>

                <div class="profile-row">
                    <span class="profile-label">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        Database Record ID
                    </span>
                    <span class="profile-value">#<?php echo $user_id; ?></span>
                </div>

                <div class="profile-row">
                    <span class="profile-label">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Session Authenticated At
                    </span>
                    <span class="profile-value"><?php echo $login_time; ?></span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 1rem;">
                <a href="logout.php" class="btn btn-danger">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout from Session
                </a>
                <a href="index.html" class="btn btn-secondary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Login Portal
                </a>
            </div>
        </div>

        <footer>
            <p>Web Programming Laboratory &bull; User Validation System</p>
        </footer>
    </div>

</body>
</html>

