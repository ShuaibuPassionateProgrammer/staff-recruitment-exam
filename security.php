<?php
// Prevent direct script access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("HTTP/1.0 403 Forbidden");
    exit('Direct access forbidden.');
}

// Function to verify session integrity
function verifySessionIntegrity() {
    if (isset($_SESSION['ip']) && isset($_SESSION['user_agent'])) {
        if ($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] ||
            $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
            // Session hijacking attempt detected
            session_unset();
            session_destroy();
            header("location:login.php?w=" . urlencode("Session authentication failed."));
            exit();
        }
    }
}

// Function to generate secure password hash
function generatePasswordHash($password) {
    return password_hash($password, PASSWORD_ARGON2ID, [
        'memory_cost' => 65536,
        'time_cost' => 4,
        'threads' => 3
    ]);
}

// Function to verify password strength
function verifyPasswordStrength($password) {
    // At least 8 characters
    if (strlen($password) < 8) {
        return false;
    }
    
    // Must contain at least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }
    
    // Must contain at least one lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }
    
    // Must contain at least one number
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }
    
    // Must contain at least one special character
    if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
        return false;
    }
    
    return true;
}

// Function to sanitize file paths
function sanitizePath($path) {
    // Remove any parent directory references
    $path = str_replace('..', '', $path);
    // Remove any Windows directory separators
    $path = str_replace('\\', '/', $path);
    // Remove any duplicate slashes
    $path = preg_replace('|/{2,}|', '/', $path);
    // Remove any slashes from the beginning
    $path = ltrim($path, '/');
    
    return $path;
}

// Function to validate file upload
function validateFileUpload($file, $allowed_types = [], $max_size = 5242880) {
    $errors = [];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        switch ($file['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errors[] = "File is too large";
                break;
            case UPLOAD_ERR_PARTIAL:
                $errors[] = "File was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $errors[] = "No file was uploaded";
                break;
            default:
                $errors[] = "Unknown upload error";
        }
        return $errors;
    }
    
    // Check file size
    if ($file['size'] > $max_size) {
        $errors[] = "File is too large (maximum " . ($max_size/1024/1024) . "MB)";
    }
    
    // Check MIME type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($file['tmp_name']);
    
    if (!empty($allowed_types) && !in_array($mime_type, $allowed_types)) {
        $errors[] = "Invalid file type";
    }
    
    return $errors;
}

// Function to generate secure filename
function generateSecureFilename($original_filename) {
    $info = pathinfo($original_filename);
    return uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $info['extension'];
}

// Function to validate date
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// Function to prevent XSS in JSON
function jsonEncodeWithXSSProtection($data) {
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}

// Function to log security events
function logSecurityEvent($event_type, $details) {
    $log_file = 'security_events.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $user = $_SESSION['email'] ?? 'anonymous';
    
    $log_entry = sprintf(
        "[%s] Type: %s | User: %s | IP: %s | Details: %s\n",
        $timestamp,
        $event_type,
        $user,
        $ip,
        $details
    );
    
    error_log($log_entry, 3, $log_file);
}

// Function to check for common attack patterns
function detectAttackPattern($input) {
    $attack_patterns = [
        // SQL Injection
        '/union\s+select/i',
        '/sleep\s*\(\s*\d+\s*\)/i',
        '/benchmark\s*\(\s*\d+/i',
        // XSS
        '/<script[^>]*>/i',
        '/javascript:/i',
        '/vbscript:/i',
        '/onclick/i',
        '/onload/i',
        // Directory Traversal
        '/\.\.[\\/]/i',
        // File Inclusion
        '/include\s*\(/i',
        '/require\s*\(/i',
        // Command Injection
        '/;\s*\$\(/i',
        '/`.*`/i'
    ];
    
    foreach ($attack_patterns as $pattern) {
        if (preg_match($pattern, $input)) {
            logSecurityEvent('ATTACK_PATTERN_DETECTED', "Pattern: $pattern, Input: " . substr($input, 0, 100));
            return true;
        }
    }
    
    return false;
}
?> 