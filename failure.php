<?php
/**
 * Login Failure Page
 * Shown when the login credentials fail validation against the database records.
 */

$error_msg = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : 'Invalid Login Name or Password.';
$username  = isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication Failed &bull; User Validation System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <div class="card">
            <!-- Animated Failure Icon -->
            <div class="status-icon-wrapper status-icon-error">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>

            <div class="card-header">
                <span class="badge badge-error">&cross; Validation Failed</span>
                <h1>Login Failed</h1>
                <p class="subtitle">The credentials you entered do not match any existing record in our database.</p>
            </div>

            <!-- Error alert box -->
            <div class="alert alert-error">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <strong>Failure Details:</strong><br>
                    <?php echo $error_msg; ?>
                    <?php if (!empty($username)): ?>
                        <div style="margin-top: 0.35rem; color: #fca5a5; font-size: 0.85rem;">
                            Submitted username: <code><?php echo $username; ?></code>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="credentials-box" style="margin-top: 0; margin-bottom: 1.5rem;">
                <div class="credentials-title">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    Troubleshooting Tips:
                </div>
                <ul style="padding-left: 1.25rem; color: var(--text-secondary); line-height: 1.6;">
                    <li>Ensure caps lock is off and spelling is correct.</li>
                    <li>Verify the login name exists in the database <code>users</code> table.</li>
                    <li>Try default sample credentials: <code>ayush</code> / <code>password123</code>.</li>
                </ul>
            </div>

            <!-- Action Return Button -->
            <a href="index.html" class="btn btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Return to Login Page &amp; Try Again
            </a>
        </div>

        <footer>
            <p>Web Programming Laboratory &bull; User Validation System</p>
        </footer>
    </div>

</body>
</html>
