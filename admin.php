<?php
    include_once 'dbConnection.php';
    include_once 'session_handler.php';

    // Verify that the request is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php");
        exit();
    }

    // Get and sanitize inputs
    $email = filter_input(INPUT_POST, 'uname', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $ref = $_GET['q'] ?? 'index.php';

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: $ref?w=" . urlencode("Invalid admin credentials"));
        exit();
    }

    try {
        // Use prepared statement to prevent SQL injection
        $stmt = $con->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            
            // Verify password
            if (md5($password) === $row['password']) { // Note: MD5 is kept for compatibility
                // Clear any existing session
                session_start();
                session_unset();
                session_destroy();
                session_start();
                
                // Set admin session variables
                $_SESSION["name"] = 'Admin';
                $_SESSION["key"] = 'admin_7785068889'; // Unique admin key
                $_SESSION["email"] = $email;
                $_SESSION["is_admin"] = true;
                $_SESSION["last_activity"] = time();
                
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                
                header("Location: dash.php?q=0");
                exit();
            }
        }
        
        // Invalid credentials
        header("Location: $ref?w=" . urlencode("Invalid admin credentials"));
        exit();
        
    } catch (Exception $e) {
        error_log("Admin login error: " . $e->getMessage());
        header("Location: $ref?w=" . urlencode("An error occurred. Please try again later."));
        exit();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
?>