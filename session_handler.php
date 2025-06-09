<?php
// Set secure session parameters
// These must be set before session_start()
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

// Only set secure cookie if HTTPS is being used
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session timeout duration (30 minutes)
define('SESSION_TIMEOUT', 1800);

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['email']) && isset($_SESSION['last_activity']);
}

// Check session timeout
function checkSessionTimeout() {
    if (isLoggedIn()) {
        if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
            // Session has expired
            session_unset();
            session_destroy();
            header("Location: index.php?w=" . urlencode("Session expired. Please login again."));
            exit();
        }
        // Update last activity time
        $_SESSION['last_activity'] = time();
    }
}

// Verify user session
function verifySession() {
    if (!isLoggedIn()) {
        header("Location: index.php");
        exit();
    }
    checkSessionTimeout();
}

// Regenerate session ID periodically
if (isLoggedIn() && rand(1, 100) <= 10) {
    session_regenerate_id(true);
}
?> 