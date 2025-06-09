<?php
// Start session with secure settings
function secureSessionStart() {
    if (session_status() === PHP_SESSION_NONE) {
        // Set secure session parameters
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_trans_sid', 0);
        ini_set('session.cache_limiter', 'nocache');
        
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            ini_set('session.cookie_secure', 1);
            ini_set('session.cookie_samesite', 'Strict');
        }
        
        session_start();
    }
}

// Initialize secure session
secureSessionStart();

// Set session timeout to 30 minutes
$session_timeout = 1800;

// Check if session is expired
function checkSessionExpiry() {
    global $session_timeout;
    
    if (isset($_SESSION['last_activity'])) {
        $inactive_time = time() - $_SESSION['last_activity'];
        
        if ($inactive_time >= $session_timeout) {
            // Session expired, destroy it
            session_unset();
            session_destroy();
            
            // Clear session cookie
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time()-3600, '/');
            }
            
            // Redirect to login with message
            header("location:login.php?w=" . urlencode("Session expired. Please login again."));
            exit();
        }
    }
    
    // Update last activity time
    $_SESSION['last_activity'] = time();
}

// Function to check user authentication
function checkUserAuth() {
    // Check if user is logged in
    if (!isset($_SESSION['email'])) {
        header("location:login.php");
        exit();
    }
    
    // Check session expiry
    checkSessionExpiry();
    
    // Get current page
    $current_page = basename($_SERVER['PHP_SELF']);
    
    // Pages accessible only to non-logged in users
    $public_pages = array('login.php', 'register.php', 'index.php', 'index1.php');
    
    // Redirect logged-in users from public pages
    if (in_array($current_page, $public_pages)) {
        if (isset($_SESSION['admin']) && $_SESSION['admin']) {
            header("location:dash.php");
        } else {
            header("location:account.php");
        }
        exit();
    }
}

// Function to check admin authentication
function checkAdminAuth() {
    // Check if user is logged in and is admin
    if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
        header("location:login.php");
        exit();
    }
    
    // Check session expiry
    checkSessionExpiry();
    
    // Get current page
    $current_page = basename($_SERVER['PHP_SELF']);
    
    // Pages not accessible to admins
    $restricted_pages = array('login.php', 'register.php', 'index.php', 'index1.php', 'account.php');
    
    if (in_array($current_page, $restricted_pages)) {
        header("location:dash.php");
        exit();
    }
}

// Function to prevent caching
function preventCaching() {
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Past date
}

// Add no-cache meta tags
function addNoCacheMetaTags() {
    echo '<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">';
    echo '<meta http-equiv="Pragma" content="no-cache">';
    echo '<meta http-equiv="Expires" content="0">';
}

// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && 
           preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
}

// Function to generate random token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Prevent direct script access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("HTTP/1.0 403 Forbidden");
    exit('Direct access forbidden.');
}

// Set secure headers
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');
header('Content-Security-Policy: default-src \'self\' https: \'unsafe-inline\' \'unsafe-eval\'');

// Prevent caching by default
preventCaching();
?> 