<?php
$login = $_POST['login'];
$inputPass = $_POST['pass'];

require 'dbconnection.php';
require 'config.php';
require 'trace.php';
require 'myfunctions.php';

$query = "SELECT * FROM users WHERE email = '$login'";
$result = mysqli_query($connection, $query);
$user = mysqli_fetch_array($result);

if ($user && verifyPassword($inputPass, $user['pass'])) {

    if ($inputPass == $user['pass']) {
        upgradePassword($connection, $login, $inputPass);
        trace("Password upgraded to md5 for $login");
    }

    session_start();
    $_SESSION['user'] = $login;
    $_SESSION['LAT'] = time();
    $_SESSION['role'] = $user['role'];

    trace("User logged in");

    if ($user['role'] == 'IT') {
        header('location:allTickets.php');
    } else {
        header('location:myTickets.php');
    }
} else {
    header('location:authForm.php?auth=false');
}

mysqli_close($connection);
?>
