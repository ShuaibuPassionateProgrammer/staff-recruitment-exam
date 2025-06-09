<?php
    include_once 'dbConnection.php';
    $ref=@$_GET['q'];
    $email = $_POST['uname'];
    $password = $_POST['password'];

    $email = stripslashes($email);
    $email = addslashes($email);
    $password = stripslashes($password);
    $password = addslashes($password);
    $password = md5($password);

    $result = mysqli_query($con,"SELECT email FROM admin WHERE email = '$email' and password = '$password'") or die('Error');
    $count=mysqli_num_rows($result);
    if($count==1){
        session_start();
        if(isset($_SESSION['email'])){
            session_unset();
            session_destroy();
            session_start();
        }
        $_SESSION["name"] = 'Admin';
        $_SESSION["key"] ='sunny7785068889';
        $_SESSION["email"] = $email;
        header("location:dash.php?q=0");
    }
    else {
        header("location:$ref?w=Warning : Access denied");
    }

    // Admin properties and features based on the codebase:
    //
    // Properties:
    // - email (string): Admin's email/username (from DB and session)
    // - password (string): Admin's password (stored as MD5 in DB)
    // - name (string): Set as 'Admin' in session
    // - key (string): Set as 'sunny7785068889' in session
    //
    // Features:
    // - Login/logout via form and session
    // - Access to dashboard (dash.php)
    // - Manage candidates, rankings, feedback, exams (add/remove)
    // - Session-based authentication and authorization
    //
    // The admin table in the database must have at least:
    //   - email (varchar)
    //   - password (varchar, MD5 hash)
    //
    // On successful login, admin session is established and redirected to dashboard.
    // On failure, redirected back with warning.
?>