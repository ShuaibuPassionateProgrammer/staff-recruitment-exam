<?php
    include_once 'dbConnection.php';
    ob_start();

    // Verify that the request is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php");
        exit();
    }

    // Get and sanitize inputs
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $college = filter_input(INPUT_POST, 'college', FILTER_SANITIZE_STRING);
    $mob = filter_input(INPUT_POST, 'mob', FILTER_SANITIZE_STRING);
    $password = $_POST['password'] ?? '';

    // Input validation
    if (empty($name) || empty($gender) || empty($email) || empty($college) || empty($mob) || empty($password)) {
        header("Location: index.php?q7=" . urlencode("All fields are required!"));
        exit();
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?q7=" . urlencode("Invalid email format!"));
        exit();
    }

    // Validate mobile number (assuming 10-15 digits)
    if (!preg_match('/^[0-9]{10,15}$/', $mob)) {
        header("Location: index.php?q7=" . urlencode("Invalid mobile number!"));
        exit();
    }

    // Format name (capitalize first letter of each word)
    $name = ucwords(strtolower($name));

    try {
        // Check if email already exists
        $stmt = $con->prepare("SELECT email FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            header("Location: index.php?q7=" . urlencode("Email Already Registered!"));
            exit();
        }
        
        // Hash password (Note: MD5 is kept for compatibility, but should be upgraded)
        $hashed_password = md5($password);
        
        // Insert new user
        $stmt = $con->prepare("INSERT INTO user (name, gender, college, email, mob, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $gender, $college, $email, $mob, $hashed_password);
        
        if ($stmt->execute()) {
            // Start session
            session_start();
            $_SESSION["email"] = $email;
            $_SESSION["name"] = $name;
            $_SESSION["last_activity"] = time();
            
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            
            header("Location: account.php?q=1");
            exit();
        } else {
            throw new Exception("Error creating user account");
        }
        
    } catch (Exception $e) {
        error_log("Signup error: " . $e->getMessage());
        header("Location: index.php?q7=" . urlencode("An error occurred. Please try again later."));
        exit();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }

    ob_end_flush();
?>