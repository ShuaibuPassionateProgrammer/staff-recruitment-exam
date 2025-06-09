<?php
require_once 'session_handler.php';
require_once 'dbConnection.php';
require_once 'security.php';

// Check if user is already logged in
if(isset($_SESSION['email'])) {
    if(isset($_SESSION['admin']) && $_SESSION['admin']) {
        header("location:dash.php");
    } else {
        header("location:account.php");
    }
    exit();
}

// Verify that the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("location:login.php");
    exit();
}

// Rate limiting
$ip = $_SERVER['REMOTE_ADDR'];
$timestamp = time();
$attempts_file = 'login_attempts.json';

// Load existing attempts
$attempts = [];
if (file_exists($attempts_file)) {
    $attempts = json_decode(file_get_contents($attempts_file), true) ?? [];
}

// Clean up old attempts (older than 1 hour)
$attempts = array_filter($attempts, function($attempt) use ($timestamp) {
    return ($timestamp - $attempt['time']) < 3600;
});

// Check if IP is blocked
if (isset($attempts[$ip])) {
    if ($attempts[$ip]['count'] >= 5) {
        $block_time = $attempts[$ip]['time'] + 3600 - $timestamp;
        if ($block_time > 0) {
            header("location:login.php?w=" . urlencode("Too many login attempts. Please try again in " . ceil($block_time/60) . " minutes."));
            exit();
        } else {
            unset($attempts[$ip]);
        }
    }
}

// Get and sanitize inputs
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';

// Validate email
if (!validateEmail($email)) {
    // Record failed attempt
    $attempts[$ip] = [
        'time' => $timestamp,
        'count' => ($attempts[$ip]['count'] ?? 0) + 1
    ];
    file_put_contents($attempts_file, json_encode($attempts));
    
    header("location:login.php?w=" . urlencode("Invalid email format"));
    exit();
}

try {
    // First check if it's an admin
    $stmt = $con->prepare("SELECT password FROM admin WHERE email = ? LIMIT 1");
    if (!$stmt) {
        throw new Exception("Database error: " . $con->error);
    }
    
    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        throw new Exception("Execution error: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // Verify admin password
        if (password_verify($password, $row['password'])) {
            // Clear login attempts
            unset($attempts[$ip]);
            file_put_contents($attempts_file, json_encode($attempts));
            
            // Set admin session variables
            $_SESSION["email"] = $email;
            $_SESSION["admin"] = true;
            $_SESSION["last_activity"] = time();
            $_SESSION["ip"] = $ip;
            $_SESSION["user_agent"] = $_SERVER['HTTP_USER_AGENT'];
            
            // Generate CSRF token
            $_SESSION["csrf_token"] = generateToken();
            
            // Regenerate session ID
            session_regenerate_id(true);
            
            // Log successful admin login
            logSecurityEvent('ADMIN_LOGIN', "Admin login successful: $email from IP: $ip");
            
            header("location:dash.php");
            exit();
        }
    }
    
    // If not admin, check regular user
    $stmt = $con->prepare("SELECT name, password FROM user WHERE email = ? LIMIT 1");
    if (!$stmt) {
        throw new Exception("Database error: " . $con->error);
    }
    
    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        throw new Exception("Execution error: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // Verify user password (using MD5 for compatibility)
        if (md5($password) === $row['password']) {
            // Clear login attempts
            unset($attempts[$ip]);
            file_put_contents($attempts_file, json_encode($attempts));
            
            // Set user session variables
            $_SESSION["name"] = $row['name'];
            $_SESSION["email"] = $email;
            $_SESSION["last_activity"] = time();
            $_SESSION["ip"] = $ip;
            $_SESSION["user_agent"] = $_SERVER['HTTP_USER_AGENT'];
            
            // Generate CSRF token
            $_SESSION["csrf_token"] = generateToken();
            
            // Regenerate session ID
            session_regenerate_id(true);
            
            // Log successful user login
            logSecurityEvent('USER_LOGIN', "User login successful: $email from IP: $ip");
            
            header("location:account.php");
            exit();
        }
    }
    
    // Record failed attempt
    $attempts[$ip] = [
        'time' => $timestamp,
        'count' => ($attempts[$ip]['count'] ?? 0) + 1
    ];
    file_put_contents($attempts_file, json_encode($attempts));
    
    // Invalid credentials
    header("location:login.php?w=" . urlencode("Invalid email or password"));
    exit();
    
} catch (Exception $e) {
    // Log the error
    error_log("Login error: " . $e->getMessage());
    
    if (DEV_MODE) {
        header("location:login.php?w=" . urlencode("Error: " . $e->getMessage()));
    } else {
        header("location:login.php?w=" . urlencode("An error occurred. Please try again later."));
    }
    exit();
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
}
?> 