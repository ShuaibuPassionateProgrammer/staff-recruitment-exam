<?php
session_start();
include_once 'dbConnection.php';

$ref = @$_GET['q'];

$email = $_POST['email'];
$password = $_POST['password'];

$email = stripslashes($email);
$password = stripslashes($password);
$email = mysqli_real_escape_string($con, $email);
$password = mysqli_real_escape_string($con, $password);

// Check Admin Login
$query_admin = "SELECT * FROM admin WHERE email='$email'";
$result_admin = mysqli_query($con, $query_admin);

if (mysqli_num_rows($result_admin) == 1) {
    $row_admin = mysqli_fetch_array($result_admin);
    if (password_verify($password, $row_admin['password'])) {
        $_SESSION['email'] = $email;
        $_SESSION['key'] = 'admin';
        header("location:admin.php?q=0");
        exit();
    } else {
        header("location:$ref?w=Invalid admin credentials");
        exit();
    }
}

// Check User Login
$query_user = "SELECT * FROM user WHERE email='$email'";
$result_user = mysqli_query($con, $query_user);

if (mysqli_num_rows($result_user) == 1) {
    $row_user = mysqli_fetch_array($result_user);
    if (password_verify($password, $row_user['password'])) {
        $_SESSION['name'] = $row_user['name'];
        $_SESSION['email'] = $email;
        header("location:account.php?q=1");
        exit();
    } else {
        header("location:$ref?w=Invalid user credentials");
        exit();
    }
}

// If no match
header("location:$ref?w=Email not found");
exit();
?>
