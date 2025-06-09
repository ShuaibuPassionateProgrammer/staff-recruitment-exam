<?php
require_once 'config.php';

try {
    // Create connection without database
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS);
    
    if ($con->connect_error) {
        throw new Exception("Connection failed: " . $con->connect_error);
    }
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
    if ($con->query($sql)) {
        echo "Database created successfully or already exists<br>";
        
        // Select the database
        if ($con->select_db(DB_NAME)) {
            echo "Database selected successfully<br>";
            
            // Include the database check script
            require_once 'check_database.php';
            
            // Run database checks
            $result = checkAndFixDatabase();
            
            // Display results
            if (!empty($result['errors'])) {
                echo "<h3>Errors:</h3><ul>";
                foreach ($result['errors'] as $error) {
                    echo "<li style='color: red;'>$error</li>";
                }
                echo "</ul>";
            }
            
            if (!empty($result['fixes'])) {
                echo "<h3>Fixes Applied:</h3><ul>";
                foreach ($result['fixes'] as $fix) {
                    echo "<li style='color: green;'>$fix</li>";
                }
                echo "</ul>";
            }
            
            if (empty($result['errors']) && empty($result['fixes'])) {
                echo "<p style='color: green;'>Database setup completed successfully.</p>";
            }
            
            echo "<p>You can now try to <a href='login.php'>login</a> with these credentials:</p>";
            echo "<ul>";
            echo "<li>Admin Login: admin@admin.com / Password: Admin@123</li>";
            echo "</ul>";
        } else {
            throw new Exception("Error selecting database: " . $con->error);
        }
    } else {
        throw new Exception("Error creating database: " . $con->error);
    }
    
} catch (Exception $e) {
    die("Setup Error: " . $e->getMessage());
} finally {
    if (isset($con)) {
        $con->close();
    }
}
?> 