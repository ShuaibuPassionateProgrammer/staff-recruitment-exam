<?php
$con = new mysqli('localhost', 'root', '', 'exam_db_001') or die("Could not connect to mysql".mysqli_error($con));
?>

<?php
echo password_hash("123456", PASSWORD_DEFAULT);