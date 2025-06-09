<?php
require_once 'dbConnection.php';
require_once 'security.php';

// Prevent direct access in production
if (!DEV_MODE && basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("HTTP/1.0 403 Forbidden");
    exit('Direct access forbidden.');
}

function checkAndFixDatabase() {
    global $con;
    $errors = [];
    $fixes = [];
    
    try {
        // Check if database exists
        $result = $con->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DB_NAME . "'");
        if ($result->num_rows === 0) {
            $errors[] = "Database '" . DB_NAME . "' does not exist";
            return ['errors' => $errors, 'fixes' => $fixes];
        }
        
        // Check admin table
        $result = $con->query("SHOW TABLES LIKE 'admin'");
        if ($result->num_rows === 0) {
            $fixes[] = "Creating admin table...";
            $sql = "CREATE TABLE `admin` (
                `email` varchar(50) NOT NULL,
                `password` varchar(255) NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            
            if ($con->query($sql)) {
                $fixes[] = "Admin table created successfully";
            } else {
                $errors[] = "Error creating admin table: " . $con->error;
            }
        }
        
        // Check if admin table has any records
        $result = $con->query("SELECT COUNT(*) as count FROM admin");
        $row = $result->fetch_assoc();
        
        if ($row['count'] == 0) {
            $fixes[] = "Creating default admin account...";
            
            // Create default admin account
            $email = 'admin@admin.com';
            $password = password_hash('Admin@123', PASSWORD_DEFAULT);
            
            $stmt = $con->prepare("INSERT INTO admin (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $password);
            
            if ($stmt->execute()) {
                $fixes[] = "Default admin account created (Email: admin@admin.com, Password: Admin@123)";
            } else {
                $errors[] = "Error creating default admin: " . $stmt->error;
            }
            $stmt->close();
        }
        
        // Check user table
        $result = $con->query("SHOW TABLES LIKE 'user'");
        if ($result->num_rows === 0) {
            $fixes[] = "Creating user table...";
            $sql = "CREATE TABLE `user` (
                `name` varchar(50) NOT NULL,
                `gender` varchar(5) NOT NULL,
                `college` varchar(100) NOT NULL,
                `email` varchar(50) NOT NULL,
                `mob` bigint(20) NOT NULL,
                `password` varchar(50) NOT NULL,
                PRIMARY KEY (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            
            if ($con->query($sql)) {
                $fixes[] = "User table created successfully";
            } else {
                $errors[] = "Error creating user table: " . $con->error;
            }
        }
        
        // Check other required tables
        $required_tables = [
            'quiz' => "CREATE TABLE `quiz` (
                `eid` varchar(100) NOT NULL,
                `title` varchar(100) NOT NULL,
                `sahi` int(11) NOT NULL,
                `wrong` int(11) NOT NULL,
                `total` int(11) NOT NULL,
                `time` bigint(20) NOT NULL,
                `intro` text NOT NULL,
                `tag` varchar(100) NOT NULL,
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`eid`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            
            'questions' => "CREATE TABLE `questions` (
                `eid` varchar(100) NOT NULL,
                `qid` varchar(100) NOT NULL,
                `qns` text NOT NULL,
                `choice` int(10) NOT NULL,
                `sn` int(11) NOT NULL,
                PRIMARY KEY (`qid`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            
            'options' => "CREATE TABLE `options` (
                `qid` varchar(50) NOT NULL,
                `option` varchar(5000) NOT NULL,
                `optionid` text NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            
            'answer' => "CREATE TABLE `answer` (
                `qid` varchar(50) NOT NULL,
                `ansid` text NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            
            'history' => "CREATE TABLE `history` (
                `email` varchar(50) NOT NULL,
                `eid` varchar(100) NOT NULL,
                `score` int(11) NOT NULL,
                `level` int(11) NOT NULL,
                `sahi` int(11) NOT NULL,
                `wrong` int(11) NOT NULL,
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            
            'rank' => "CREATE TABLE `rank` (
                `email` varchar(50) NOT NULL,
                `score` int(11) NOT NULL,
                `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        ];
        
        foreach ($required_tables as $table => $create_sql) {
            $result = $con->query("SHOW TABLES LIKE '$table'");
            if ($result->num_rows === 0) {
                $fixes[] = "Creating $table table...";
                if ($con->query($create_sql)) {
                    $fixes[] = "$table table created successfully";
                } else {
                    $errors[] = "Error creating $table table: " . $con->error;
                }
            }
        }
        
    } catch (Exception $e) {
        $errors[] = "Error: " . $e->getMessage();
    }
    
    return ['errors' => $errors, 'fixes' => $fixes];
}

// Run the check if accessed directly
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    $result = checkAndFixDatabase();
    
    echo "<h2>Database Check Results</h2>";
    
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
        echo "<p style='color: green;'>Database check completed. No issues found.</p>";
    }
}
?> 