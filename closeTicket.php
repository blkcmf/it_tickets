<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'IT') {
    header('location:authForm.php?auth=role');
}

require 'dbconnection.php';
require 'trace.php';
require 'myfunctions.php';

$number = $_GET['num'];
$itUser = $_SESSION['user'];
$closedAt = date('Y-m-d H:i:s');

// 1. Check ticket exists and is assigned to this IT staff
$query = "SELECT * FROM tickets WHERE id=$number AND status='assigned' AND assigned_to='$itUser'";
$result = mysqli_query($connection, $query);
$ticket = mysqli_fetch_array($result);

if (!$ticket) {
    header('location:allTickets.php');
    exit;
}

// 2. Close the ticket (mark as solved)
$query = "UPDATE tickets SET status='closed', work_status='solved', closed_at='$closedAt', closed_by='$itUser' WHERE id=$number";
mysqli_query($connection, $query);
trace("Ticket #$number closed by $itUser");

// 3. Free the IT staff
freeITStaff($connection, $itUser);

// 4. Give them the next ticket on hold (if any)
tryAssignPendingTicket($connection, $itUser);

mysqli_close($connection);
header('location:allTickets.php');
?>
