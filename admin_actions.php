<?php
include_once 'dbConnection.php';
include_once 'admin_middleware.php';

// Verify admin session
verifyAdminSession();

// Function to handle exam creation
function createExam($data) {
    global $con;
    
    // Validate input
    $errors = validateExamInput($data);
    if (!empty($errors)) {
        return ['success' => false, 'message' => implode(', ', $errors)];
    }
    
    try {
        // Sanitize inputs
        $name = $con->real_escape_string($data['name']);
        $total = (int)$data['total'];
        $right = (float)$data['right'];
        $wrong = (float)$data['wrong'];
        $time = (int)$data['time'];
        $eid = uniqid();
        
        // Start transaction
        $con->begin_transaction();
        
        // Insert exam
        $query = "INSERT INTO quiz (eid, title, sahi, wrong, total, time, date) 
                  VALUES ('$eid', '$name', $right, $wrong, $total, $time, NOW())";
        
        if (!$con->query($query)) {
            throw new Exception("Error creating exam");
        }
        
        // Log action
        logAdminAction('create_exam', "Created exam: $name");
        
        $con->commit();
        return [
            'success' => true,
            'message' => 'Exam created successfully',
            'eid' => $eid,
            'total' => $total
        ];
        
    } catch (Exception $e) {
        $con->rollback();
        error_log("Error creating exam: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error creating exam'];
    }
}

// Function to handle question addition
function addQuestion($data, $eid, $qno) {
    global $con;
    
    // Validate input
    $errors = validateQuestionInput($data);
    if (!empty($errors)) {
        return ['success' => false, 'message' => implode(', ', $errors)];
    }
    
    try {
        // Sanitize inputs
        $qns = $con->real_escape_string($data['qns']);
        $choice = array_map(function($ch) use ($con) {
            return $con->real_escape_string($ch);
        }, $data['choices']);
        
        $ans = $data['ans'];
        if (!in_array($ans, ['a', 'b', 'c', 'd'])) {
            return ['success' => false, 'message' => 'Invalid answer choice'];
        }
        
        // Start transaction
        $con->begin_transaction();
        
        // Insert question
        $query = "INSERT INTO questions (eid, qid, qns, sn) VALUES ('$eid', NULL, '$qns', $qno)";
        if (!$con->query($query)) {
            throw new Exception("Error adding question");
        }
        
        $qid = $con->insert_id;
        
        // Insert options
        $optionKeys = ['a', 'b', 'c', 'd'];
        foreach ($optionKeys as $i => $key) {
            if (!empty($choice[$i])) {
                $option = $choice[$i];
                $query = "INSERT INTO options (qid, option, optionid) VALUES ($qid, '$option', '$key')";
                if (!$con->query($query)) {
                    throw new Exception("Error adding option");
                }
            }
        }
        
        // Insert answer
        $query = "INSERT INTO answer (qid, ansid) VALUES ($qid, '$ans')";
        if (!$con->query($query)) {
            throw new Exception("Error adding answer");
        }
        
        // Log action
        logAdminAction('add_question', "Added question to exam: $eid");
        
        $con->commit();
        return ['success' => true, 'message' => 'Question added successfully'];
        
    } catch (Exception $e) {
        $con->rollback();
        error_log("Error adding question: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error adding question'];
    }
}

// Function to delete exam
function deleteExam($eid) {
    global $con;
    
    try {
        // Start transaction
        $con->begin_transaction();
        
        // Get exam details for logging
        $result = mysqli_query($con, "SELECT title FROM quiz WHERE eid='$eid'");
        $exam = mysqli_fetch_array($result);
        
        // Delete related records
        $tables = ['questions', 'answer', 'options', 'history', 'quiz'];
        foreach ($tables as $table) {
            $query = "DELETE FROM $table WHERE " . ($table === 'options' ? "qid IN (SELECT qid FROM questions WHERE eid='$eid')" : "eid='$eid'");
            if (!$con->query($query)) {
                throw new Exception("Error deleting from $table");
            }
        }
        
        // Log action
        logAdminAction('delete_exam', "Deleted exam: {$exam['title']}");
        
        $con->commit();
        return ['success' => true, 'message' => 'Exam deleted successfully'];
        
    } catch (Exception $e) {
        $con->rollback();
        error_log("Error deleting exam: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error deleting exam'];
    }
}

// Function to delete user
function deleteUser($email) {
    global $con;
    
    try {
        // Start transaction
        $con->begin_transaction();
        
        // Get user details for logging
        $result = mysqli_query($con, "SELECT name FROM user WHERE email='$email'");
        $user = mysqli_fetch_array($result);
        
        // Delete related records
        $tables = ['history', 'rank', 'user'];
        foreach ($tables as $table) {
            $query = "DELETE FROM $table WHERE email='$email'";
            if (!$con->query($query)) {
                throw new Exception("Error deleting from $table");
            }
        }
        
        // Log action
        logAdminAction('delete_user', "Deleted user: {$user['name']} ($email)");
        
        $con->commit();
        return ['success' => true, 'message' => 'User deleted successfully'];
        
    } catch (Exception $e) {
        $con->rollback();
        error_log("Error deleting user: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error deleting user'];
    }
}

// Function to delete feedback
function deleteFeedback($id) {
    global $con;
    
    try {
        // Get feedback details for logging
        $result = mysqli_query($con, "SELECT subject FROM feedback WHERE id=$id");
        $feedback = mysqli_fetch_array($result);
        
        // Delete feedback
        $query = "DELETE FROM feedback WHERE id=$id";
        if (!$con->query($query)) {
            throw new Exception("Error deleting feedback");
        }
        
        // Log action
        logAdminAction('delete_feedback', "Deleted feedback: {$feedback['subject']}");
        
        return ['success' => true, 'message' => 'Feedback deleted successfully'];
        
    } catch (Exception $e) {
        error_log("Error deleting feedback: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error deleting feedback'];
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $response = ['success' => false, 'message' => 'Invalid action'];
    
    switch ($action) {
        case 'create_exam':
            $response = createExam($_POST);
            break;
            
        case 'add_question':
            $eid = $_POST['eid'] ?? '';
            $qno = $_POST['qno'] ?? 0;
            $response = addQuestion($_POST, $eid, $qno);
            break;
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    $response = ['success' => false, 'message' => 'Invalid action'];
    
    switch ($action) {
        case 'delete_exam':
            $eid = $_GET['eid'] ?? '';
            $response = deleteExam($eid);
            break;
            
        case 'delete_user':
            $email = $_GET['email'] ?? '';
            $response = deleteUser($email);
            break;
            
        case 'delete_feedback':
            $id = $_GET['id'] ?? 0;
            $response = deleteFeedback($id);
            break;
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?> 