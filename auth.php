<?php
require_once 'session_handler.php';
require_once 'dbConnection.php';

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

// Get and sanitize inputs
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("location:login.php?w=" . urlencode("Invalid email format"));
    exit();
}

try {
    // First check if it's an admin
    $stmt = $con->prepare("SELECT password FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // Verify admin password
        if (password_verify($password, $row['password'])) {
            // Set admin session variables
            $_SESSION["email"] = $email;
            $_SESSION["admin"] = true;
            $_SESSION["last_activity"] = time();
            
            // Regenerate session ID
            session_regenerate_id(true);
            
            header("location:dash.php");
            exit();
        }
    }
    
    // If not admin, check regular user
    $stmt = $con->prepare("SELECT name, password FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // Verify user password (using MD5 for compatibility)
        if (md5($password) === $row['password']) {
            // Set user session variables
            $_SESSION["name"] = $row['name'];
            $_SESSION["email"] = $email;
            $_SESSION["last_activity"] = time();
            
            // Regenerate session ID
            session_regenerate_id(true);
            
            header("location:account.php");
            exit();
        }
    }
    
    // Invalid credentials
    header("location:login.php?w=" . urlencode("Invalid email or password"));
    exit();
    
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    header("location:login.php?w=" . urlencode("An error occurred. Please try again later."));
    exit();
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
}
?> 