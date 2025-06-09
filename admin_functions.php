<?php
require_once 'dbConnection.php';
require_once 'security.php';
require_once 'admin_middleware.php';

// Prevent direct script access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("HTTP/1.0 403 Forbidden");
    exit('Direct access forbidden.');
}

/**
 * Admin Functions Class
 * Contains all admin-specific functions with proper security measures
 */
class AdminFunctions {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Add new exam
     */
    public function addExam($title, $total_questions, $marks_per_right, $marks_per_wrong, $time_limit) {
        try {
            // Input validation
            if (empty($title) || !is_numeric($total_questions) || !is_numeric($marks_per_right) || 
                !is_numeric($marks_per_wrong) || !is_numeric($time_limit)) {
                throw new Exception("Invalid input parameters");
            }
            
            // Generate unique exam ID
            $eid = uniqid('exam_', true);
            
            $stmt = $this->db->prepare("INSERT INTO quiz (eid, title, total, right_mark, wrong_mark, time) 
                                      VALUES (?, ?, ?, ?, ?, ?)");
            
            $stmt->bind_param("ssiiii", $eid, $title, $total_questions, $marks_per_right, 
                            $marks_per_wrong, $time_limit);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to add exam");
            }
            
            logAdminAction('ADD_EXAM', "Added exam: $title");
            return $eid;
            
        } catch (Exception $e) {
            error_log("Admin error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Delete exam and all related data
     */
    public function deleteExam($eid) {
        try {
            // Start transaction
            $this->db->beginTransaction();
            
            // Delete questions
            $stmt = $this->db->prepare("DELETE FROM questions WHERE eid = ?");
            $stmt->bind_param("s", $eid);
            $stmt->execute();
            
            // Delete options
            $stmt = $this->db->prepare("DELETE FROM options WHERE qid IN 
                                      (SELECT qid FROM questions WHERE eid = ?)");
            $stmt->bind_param("s", $eid);
            $stmt->execute();
            
            // Delete answers
            $stmt = $this->db->prepare("DELETE FROM answer WHERE qid IN 
                                      (SELECT qid FROM questions WHERE eid = ?)");
            $stmt->bind_param("s", $eid);
            $stmt->execute();
            
            // Delete exam history
            $stmt = $this->db->prepare("DELETE FROM history WHERE eid = ?");
            $stmt->bind_param("s", $eid);
            $stmt->execute();
            
            // Delete quiz
            $stmt = $this->db->prepare("DELETE FROM quiz WHERE eid = ?");
            $stmt->bind_param("s", $eid);
            $stmt->execute();
            
            $this->db->commit();
            logAdminAction('DELETE_EXAM', "Deleted exam: $eid");
            
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Admin error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Add question to exam
     */
    public function addQuestion($eid, $question, $options, $correct_option) {
        try {
            if (empty($eid) || empty($question) || !is_array($options) || count($options) < 2 || 
                !isset($options[$correct_option])) {
                throw new Exception("Invalid question parameters");
            }
            
            // Generate question ID
            $qid = uniqid('q_', true);
            
            // Add question
            $stmt = $this->db->prepare("INSERT INTO questions (eid, qid, qns) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $eid, $qid, $question);
            $stmt->execute();
            
            // Add options
            foreach ($options as $index => $option) {
                $optionid = uniqid('opt_', true);
                $stmt = $this->db->prepare("INSERT INTO options (qid, option, optionid) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $qid, $option, $optionid);
                $stmt->execute();
                
                // Add correct answer
                if ($index === $correct_option) {
                    $stmt = $this->db->prepare("INSERT INTO answer (qid, ansid) VALUES (?, ?)");
                    $stmt->bind_param("ss", $qid, $optionid);
                    $stmt->execute();
                }
            }
            
            logAdminAction('ADD_QUESTION', "Added question to exam: $eid");
            return $qid;
            
        } catch (Exception $e) {
            error_log("Admin error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get exam statistics
     */
    public function getExamStats($eid) {
        try {
            $stmt = $this->db->prepare("SELECT 
                                          COUNT(DISTINCT h.email) as total_attempts,
                                          AVG(h.score) as avg_score,
                                          MIN(h.score) as min_score,
                                          MAX(h.score) as max_score
                                      FROM history h 
                                      WHERE h.eid = ?");
            
            $stmt->bind_param("s", $eid);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_assoc();
            
        } catch (Exception $e) {
            error_log("Admin error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get user feedback with pagination
     */
    public function getFeedback($page = 1, $per_page = 10) {
        try {
            $offset = ($page - 1) * $per_page;
            
            $stmt = $this->db->prepare("SELECT * FROM feedback 
                                      ORDER BY date DESC, time DESC 
                                      LIMIT ? OFFSET ?");
            
            $stmt->bind_param("ii", $per_page, $offset);
            $stmt->execute();
            
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            
        } catch (Exception $e) {
            error_log("Admin error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Update exam details
     */
    public function updateExam($eid, $title, $total_questions, $marks_per_right, $marks_per_wrong, $time_limit) {
        try {
            // Input validation
            if (empty($eid) || empty($title) || !is_numeric($total_questions) || 
                !is_numeric($marks_per_right) || !is_numeric($marks_per_wrong) || 
                !is_numeric($time_limit)) {
                throw new Exception("Invalid input parameters");
            }
            
            $stmt = $this->db->prepare("UPDATE quiz 
                                      SET title = ?, total = ?, right_mark = ?, 
                                          wrong_mark = ?, time = ? 
                                      WHERE eid = ?");
            
            $stmt->bind_param("siiiii", $title, $total_questions, $marks_per_right, 
                            $marks_per_wrong, $time_limit, $eid);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to update exam");
            }
            
            logAdminAction('UPDATE_EXAM', "Updated exam: $eid");
            
        } catch (Exception $e) {
            error_log("Admin error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get user ranking
     */
    public function getUserRanking($limit = 10) {
        try {
            $stmt = $this->db->prepare("SELECT 
                                          u.name,
                                          u.email,
                                          COUNT(h.eid) as exams_taken,
                                          AVG(h.score) as avg_score,
                                          SUM(h.score) as total_score
                                      FROM user u
                                      LEFT JOIN history h ON u.email = h.email
                                      GROUP BY u.email
                                      ORDER BY total_score DESC, avg_score DESC
                                      LIMIT ?");
            
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            
        } catch (Exception $e) {
            error_log("Admin error: " . $e->getMessage());
            throw $e;
        }
    }
} 