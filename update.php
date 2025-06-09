<?php
include_once 'dbConnection.php';
include_once 'admin_middleware.php';
include_once 'admin_functions.php';

// Verify admin session
verifyAdminSession();

// Handle exam management
if(isset($_GET['q']) && $_GET['q'] == 'addquiz') {
    $name = $_POST['name'];
    $total = $_POST['total'];
    $right = $_POST['right'];
    $wrong = $_POST['wrong'];
    $time = $_POST['time'];
    
    $result = addExam($con, $name, $total, $right, $wrong, $time);
    
    if($result['success']) {
        header("location:dash.php?q=4&step=2&eid=" . $result['eid'] . "&n=" . $total);
    } else {
        header("location:dash.php?q=4&step=1&w=Error:" . urlencode($result['error']));
    }
    exit();
}

// Handle question addition
if(isset($_GET['q']) && $_GET['q'] == 'addqns') {
    $eid = $_GET['eid'];
    $n = $_POST['n'];
    $qns = $_POST['qns'];
    $options = array(
        'a' => $_POST['a'],
        'b' => $_POST['b'],
        'c' => $_POST['c'],
        'd' => $_POST['d']
    );
    $ans = $_POST['ans'];
    
    $result = addQuestion($con, $eid, $qns, $options, $ans, $n);
    
    if($result['success']) {
        if($n == $_GET['total']) {
            header("location:dash.php?q=0");
        } else {
            header("location:dash.php?q=4&step=2&eid=$eid&n=" . ($n + 1) . "&total=" . $_GET['total']);
        }
    } else {
        header("location:dash.php?q=4&step=2&eid=$eid&n=$n&w=Error:" . urlencode($result['error']));
    }
    exit();
}

// Handle exam deletion
if(isset($_GET['q']) && $_GET['q'] == 'rmquiz') {
    $eid = $_GET['eid'];
    $result = deleteExam($con, $eid);
    
    if($result['success']) {
        header("location:dash.php?q=5");
    } else {
        header("location:dash.php?q=5&w=Error:" . urlencode($result['error']));
    }
    exit();
}

// Handle user deletion
if(isset($_GET['demail'])) {
    $email = $_GET['demail'];
    $result = deleteUser($con, $email);
    
    if($result['success']) {
        header("location:dash.php?q=1");
    } else {
        header("location:dash.php?q=1&w=Error:" . urlencode($result['error']));
    }
    exit();
}

// Handle feedback deletion
if(isset($_GET['fdid'])) {
    $id = $_GET['fdid'];
    if(deleteFeedback($con, $id)) {
        header("location:dash.php?q=3");
    } else {
        header("location:dash.php?q=3&w=Error:" . urlencode(mysqli_error($con)));
    }
    exit();
}

// Default redirect
header("location:dash.php?q=0");
?>



