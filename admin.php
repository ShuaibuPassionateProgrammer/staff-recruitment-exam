<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("location:dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <form method="post" action="admin_login.php">
        <input type="email" name="email" placeholder="Enter email" required><br>
        <input type="password" name="password" placeholder="Enter password" required><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
