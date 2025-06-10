<?php
include_once 'dbConnection.php';

$name = $_POST['name'];
$gender = $_POST['gender'];
$college = $_POST['college'];
$email = $_POST['email'];
$mob = $_POST['mob'];
$password = $_POST['password'];

// Secure password hashing
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO user (name, gender, college, email, mob, password) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($query);
$stmt->bind_param("ssssis", $name, $gender, $college, $email, $mob, $hashed_password);

if ($stmt->execute()) {
    header("location:index.php?q=account.php");
    exit();
} else {
    header("location:index.php?q=error");
    exit();
}
?>
