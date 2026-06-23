<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user'])) {
    header('location:authForm.php?auth=nonAuth');
    exit;
}

if (time() - $_SESSION['LAT'] > $ttl) {
    header('location:disconnect.php');
    exit;
}

$_SESSION['LAT'] = time();
?>
