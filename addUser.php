<?php
$email = $_POST['email'];
$pass = $_POST['pass'];
$pass2 = $_POST['pass2'];

if ($pass != $pass2) {
    header('location:registerForm.php?error=pass');
    exit;
}

require 'dbconnection.php';
require 'trace.php';
require 'myfunctions.php';

$query = "SELECT count(*) as number FROM users WHERE email = '$email'";
$result = mysqli_query($connection, $query);
$data = mysqli_fetch_array($result);

if ($data['number'] > 0) {
    header('location:registerForm.php?error=exists');
    exit;
}

$passHash = hashPassword($pass);
$query = "INSERT INTO users (email, pass, role, is_busy) VALUES ('$email', '$passHash', 'EMP', 0)";
mysqli_query($connection, $query);

session_start();
$_SESSION['user'] = $email;
trace("New user registered: $email");
session_destroy();

mysqli_close($connection);
header('location:authForm.php?auth=registered');
?>
