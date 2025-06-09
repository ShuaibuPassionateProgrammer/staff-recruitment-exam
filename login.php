<?php
session_start();

// Clear any existing session
if(isset($_SESSION["email"])){
    session_destroy();
    session_start();
}

include_once 'dbConnection.php';

// Verify that the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// Get and sanitize inputs
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';
$ref = $_GET['q'] ?? 'index.php';

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: $ref?w=" . urlencode("Invalid email format"));
    exit();
}

try {
    // Use prepared statement to prevent SQL injection
    $stmt = $con->prepare("SELECT name, password FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // Verify password
        if (md5($password) === $row['password']) { // Note: MD5 is kept for compatibility, but should be upgraded
            // Set session variables
            $_SESSION["name"] = $row['name'];
            $_SESSION["email"] = $email;
            $_SESSION["last_activity"] = time();
            
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            
            header("Location: account.php?q=1");
            exit();
        }
    }
    
    // Invalid credentials
    header("Location: $ref?w=" . urlencode("Wrong Username or Password"));
    exit();
    
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    header("Location: $ref?w=" . urlencode("An error occurred. Please try again later."));
    exit();
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
}
?>