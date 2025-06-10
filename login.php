<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if(isset($_SESSION["email"])){
    session_unset();
    session_destroy();
    session_start();
}
include_once 'dbConnection.php';
$ref = isset($_GET['q']) ? $_GET['q'] : 'index.php';

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("location:$ref?w=Invalid request method");
    exit();
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if ($email === '' || $password === '') {
    header("location:$ref?w=Please enter both email and password");
    exit();
}

$email = mysqli_real_escape_string($con, $email);
$password = md5($password);

// Check user table
$stmt = mysqli_prepare($con, "SELECT name FROM user WHERE email = ? AND password = ?");
if (!$stmt) {
    die("User query prepare failed: " . mysqli_error($con));
}
mysqli_stmt_bind_param($stmt, "ss", $email, $password);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if(mysqli_stmt_num_rows($stmt) == 1){
    mysqli_stmt_bind_result($stmt, $name);
    mysqli_stmt_fetch($stmt);
    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;
    $_SESSION["is_admin"] = false;
    mysqli_stmt_close($stmt);
    header("location:account.php?q=1");
    exit();
}
mysqli_stmt_close($stmt);

// Check admin table
$stmt = mysqli_prepare($con, "SELECT name FROM admin WHERE email = ? AND password = ?");
if (!$stmt) {
    die("Admin query prepare failed: " . mysqli_error($con));
}
mysqli_stmt_bind_param($stmt, "ss", $email, $password);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if(mysqli_stmt_num_rows($stmt) == 1){
    mysqli_stmt_bind_result($stmt, $name);
    mysqli_stmt_fetch($stmt);
    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;
    $_SESSION["is_admin"] = true;
    mysqli_stmt_close($stmt);
    header("location:admin.php?q=1");
    exit();
}
mysqli_stmt_close($stmt);

header("location:$ref?w=Wrong Username or Password");
exit();
?>