<?php 
session_start();
// session_unset("user");
session_unset();
// setcookie('username', '', 0, '/');
// setcookie('jwt', '', 0, '/');
header('location:../index.php');
?>