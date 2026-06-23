<?php
session_start();
require 'trace.php';
trace("User logged out");
unset($_SESSION['user']);
session_destroy();
header('location:authForm.php?auth=again');
?>
