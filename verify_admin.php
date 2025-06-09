<?php
require_once 'config.php';
require_once 'security.php';

try {
    // Create connection
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($con->connect_error) {
        throw new Exception("Connection failed: " . $con->connect_error);
    }
    
    // Check if admin table exists
    $result = $con->query("SHOW TABLES LIKE 'admin'");
    if ($result->num_rows === 0) {
        echo "<p style='color: red;'>Admin table does not exist. Creating it now...</p>";
        
        $sql = "CREATE TABLE `admin` (
            `email` varchar(50) NOT NULL,
            `password` varchar(255) NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        if ($con->query($sql)) {
            echo "<p style='color: green;'>Admin table created successfully</p>";
        } else {
            throw new Exception("Error creating admin table: " . $con->error);
        }
    }
    
    // Check if admin account exists
    $result = $con->query("SELECT COUNT(*) as count FROM admin");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        echo "<p style='color: red;'>No admin account found. Creating default admin account...</p>";
        
        // Create default admin account
        $email = 'admin@admin.com';
        $password = password_hash('Admin@123', PASSWORD_DEFAULT);
        
        $stmt = $con->prepare("INSERT INTO admin (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $password);
        
        if ($stmt->execute()) {
            echo "<p style='color: green;'>Default admin account created successfully</p>";
            echo "<p>You can now login with:</p>";
            echo "<ul>";
            echo "<li>Email: admin@admin.com</li>";
            echo "<li>Password: Admin@123</li>";
            echo "</ul>";
        } else {
            throw new Exception("Error creating admin account: " . $stmt->error);
        }
        $stmt->close();
    } else {
        echo "<p style='color: green;'>Admin account exists</p>";
        
        // Verify if default admin exists
        $stmt = $con->prepare("SELECT email FROM admin WHERE email = ?");
        $email = 'admin@admin.com';
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo "<p style='color: orange;'>Note: The default admin account (admin@admin.com) does not exist. A different admin account is being used.</p>";
        } else {
            echo "<p style='color: green;'>Default admin account (admin@admin.com) exists</p>";
            echo "<p>You can login with:</p>";
            echo "<ul>";
            echo "<li>Email: admin@admin.com</li>";
            echo "<li>Password: Admin@123</li>";
            echo "</ul>";
        }
        $stmt->close();
    }
    
} catch (Exception $e) {
    die("<p style='color: red;'>Error: " . $e->getMessage() . "</p>");
} finally {
    if (isset($con)) {
        $con->close();
    }
}
?> 