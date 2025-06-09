<?php
//all the variables defined here are accessible in all the files that include this one

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'project');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
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
    // Log error and show generic message
    error_log("Database Error: " . $e->getMessage());
    die("A database error occurred. Please try again later.");
}

// Prevent direct script access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("HTTP/1.0 403 Forbidden");
    exit('Direct access forbidden.');
}
?>