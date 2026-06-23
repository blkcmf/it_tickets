<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'IT') {
    header('location:authForm.php?auth=role');
}

require 'dbconnection.php';
require 'trace.php';

$number = $_GET['num'];
$itUser = $_SESSION['user'];
$workStatus = $_POST['work_status'];
$itNotes = mysqli_real_escape_string($connection, $_POST['it_notes']);

// 1. Check ticket is assigned to this IT staff
$query = "SELECT * FROM tickets WHERE id=$number AND status='assigned' AND assigned_to='$itUser'";
$result = mysqli_query($connection, $query);
$ticket = mysqli_fetch_array($result);

if (!$ticket) {
    header('location:allTickets.php');
    exit;
}

// 2. Update work progress
$query = "UPDATE tickets SET work_status='$workStatus', it_notes='$itNotes' WHERE id=$number";
mysqli_query($connection, $query);
trace("Ticket #$number updated: $workStatus by $itUser");

mysqli_close($connection);
header('location:showTicket.php?num=' . $number . '&updated=1');
?>
