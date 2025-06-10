<?php
session_start();
include_once 'dbConnection.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch hashed password from user table
    $query = "SELECT * FROM user WHERE email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION["email"] = $email;
            $_SESSION["name"] = $row['name'];
            $_SESSION["college"] = $row['college'];
            header("location:account.php?q=1");
            exit();
        } else {
            header("location:index.php?w=Wrong Password");
            exit();
        }
    } else {
        header("location:index.php?w=No such user");
        exit();
    }
}
?>
