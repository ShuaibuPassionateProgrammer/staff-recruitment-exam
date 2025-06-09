<?php
include_once 'session_handler.php';

function verifyAdminSession() {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if user is logged in and is admin
    if (!isset($_SESSION['email']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
        header("Location: index.php?w=" . urlencode("Access denied. Admin privileges required."));
        exit();
    }
    
    // Verify admin key
    if (!isset($_SESSION['key']) || $_SESSION['key'] !== 'admin_7785068889') {
        session_unset();
        session_destroy();
        header("Location: index.php?w=" . urlencode("Invalid admin session."));
        exit();
    }
    
    // Check session timeout
    checkSessionTimeout();
}

// Function to sanitize output
function sanitizeOutput($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Function to validate exam input
function validateExamInput($data) {
    $errors = [];
    
    if (empty($data['name'])) {
        $errors[] = "Exam title is required";
    }
    
    if (empty($data['total']) || !is_numeric($data['total']) || $data['total'] < 1) {
        $errors[] = "Valid number of questions is required";
    }
    
    if (empty($data['right']) || !is_numeric($data['right']) || $data['right'] < 0) {
        $errors[] = "Valid marks for right answer is required";
    }
    
    if (empty($data['wrong']) || !is_numeric($data['wrong']) || $data['wrong'] < 0) {
        $errors[] = "Valid marks for wrong answer is required";
    }
    
    if (empty($data['time']) || !is_numeric($data['time']) || $data['time'] < 1) {
        $errors[] = "Valid time limit is required";
    }
    
    return $errors;
}

// Function to validate question input
function validateQuestionInput($data) {
    $errors = [];
    
    if (empty($data['qns'])) {
        $errors[] = "Question is required";
    }
    
    if (empty($data['choice1']) || empty($data['choice2'])) {
        $errors[] = "At least two choices are required";
    }
    
    if (empty($data['ans'])) {
        $errors[] = "Correct answer is required";
    }
    
    return $errors;
}

// Function to log admin actions
function logAdminAction($action, $details = '') {
    global $con;
    
    $admin_email = $_SESSION['email'];
    $action = $con->real_escape_string($action);
    $details = $con->real_escape_string($details);
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    $query = "INSERT INTO admin_logs (admin_email, action, details, ip_address) 
              VALUES ('$admin_email', '$action', '$details', '$ip_address')";
    
    mysqli_query($con, $query);
}
?> 