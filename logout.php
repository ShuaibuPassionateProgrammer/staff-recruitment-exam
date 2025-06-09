<?php 
session_start();
session_unset();
session_destroy();
$ref= @$_GET['q'];
header("location:$ref");
?>