<?php
//all the variables defined here are accessible in all the files that include this one
require_once 'config.php';

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', DEV_MODE ? 1 : 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

try {
    // Create connection with error handling
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($con->connect_error) {
        throw new Exception("Connection failed: " . $con->connect_error);
    }
    
    // Set charset to prevent injection
    if (!$con->set_charset("utf8mb4")) {
        throw new Exception("Error setting charset: " . $con->error);
    }
    
    // Set SQL mode for better compatibility
    if (!$con->query("SET SESSION sql_mode = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'")) {
        throw new Exception("Error setting SQL mode: " . $con->error);
    }
    
    // Function to safely close database connection
    function closeDbConnection() {
        global $con;
        if (isset($con) && $con instanceof mysqli) {
            $con->close();
        }
    }
    
    // Register shutdown function to ensure connection is closed
    register_shutdown_function('closeDbConnection');
    
} catch (Exception $e) {
    // Log error
    error_log("Database Error: " . $e->getMessage());
    
    if (DEV_MODE) {
        // Show detailed error in development
        die("Database Error: " . $e->getMessage());
    } else {
        // Show generic error in production
        die("A database error occurred. Please try again later.");
    }
}

// Function to check if table exists
function tableExists($tableName) {
    global $con;
    $result = $con->query("SHOW TABLES LIKE '" . $con->real_escape_string($tableName) . "'");
    return $result->num_rows > 0;
}

// Check if admin table exists and create if it doesn't
if (!tableExists('admin')) {
    $sql = "CREATE TABLE IF NOT EXISTS `admin` (
        `email` varchar(50) NOT NULL,
        `password` varchar(255) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    if (!$con->query($sql)) {
        error_log("Error creating admin table: " . $con->error);
        if (DEV_MODE) {
            die("Error creating admin table: " . $con->error);
        }
    }
    
    // Create default admin account if table is empty
    $result = $con->query("SELECT COUNT(*) as count FROM admin");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        // Create default admin (email: admin@admin.com, password: Admin@123)
        $email = 'admin@admin.com';
        $password = password_hash('Admin@123', PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO admin (email, password) VALUES (?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        
        if (!$stmt->execute()) {
            error_log("Error creating default admin: " . $stmt->error);
            if (DEV_MODE) {
                die("Error creating default admin: " . $stmt->error);
            }
        }
        $stmt->close();
    }
}

// Prevent direct script access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("HTTP/1.0 403 Forbidden");
    exit('Direct access forbidden.');
}
?>