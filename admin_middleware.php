<?php
session_start();

// Function to verify admin session
function verifyAdminSession() {
    if(!isset($_SESSION['admin']) || !$_SESSION['admin']) {
        header("location:admin.php");
        exit();
    }
}

// Function to sanitize output
function sanitizeOutput($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Function to generate CSRF token
function generateCSRFToken() {
    if(!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Function to verify CSRF token
function verifyCSRFToken($token) {
    if(!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        header("HTTP/1.1 403 Forbidden");
        exit('CSRF token validation failed');
    }
}

// Function to check if request is AJAX
function isAjaxRequest() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

// Function to send JSON response
function sendJsonResponse($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit();
}

// Function to log admin actions
function logAdminAction($action, $details = '') {
    $log_file = 'admin_actions.log';
    $timestamp = date('Y-m-d H:i:s');
    $admin_email = $_SESSION['email'] ?? 'unknown';
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $log_entry = sprintf(
        "[%s] Admin: %s | IP: %s | Action: %s | Details: %s\n",
        $timestamp,
        $admin_email,
        $ip,
        $action,
        $details
    );
    
    error_log($log_entry, 3, $log_file);
}

// Set secure headers
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');
header('Content-Security-Policy: default-src \'self\' https: \'unsafe-inline\' \'unsafe-eval\'');

// Set secure cookie parameters if HTTPS
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_samesite', 'Strict');
}
?> 